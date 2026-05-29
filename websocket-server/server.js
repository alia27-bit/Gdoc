import { WebSocketServer, WebSocket } from 'ws';

const PORT = 6001;

const wss = new WebSocketServer({
    port: PORT,
    host: '0.0.0.0',
});

const clients = new Map();

// --- Heartbeat: detect dead connections ---
const HEARTBEAT_INTERVAL = 30000;

function heartbeat() {
    this.isAlive = true;
}

const heartbeatTimer = setInterval(() => {
    wss.clients.forEach((ws) => {
        if (ws.isAlive === false) {
            console.log('HEARTBEAT: koneksi mati, ditutup paksa');
            return ws.terminate();
        }
        ws.isAlive = false;
        ws.ping();
    });
}, HEARTBEAT_INTERVAL);

wss.on('close', () => {
    clearInterval(heartbeatTimer);
});

// --- Only log success AFTER the server actually binds ---
wss.on('listening', () => {
    console.log(`WebSocket berjalan di ws://0.0.0.0:${PORT}`);
});

wss.on('error', (err) => {
    if (err.code === 'EADDRINUSE') {
        console.error(`Port ${PORT} sedang dipakai. Server realtime mungkin sudah berjalan.`);
        console.error('Pakai server yang sudah ada, atau hentikan proses node lama sebelum menjalankan yang baru.');
        process.exit(1);
    }

    console.error(err);
    process.exit(1);
});

wss.on('connection', (ws, req) => {
    const path = req.url || '/';
    const roomId = path.replace(/^\/+/g, '');

    ws.isAlive = true;
    ws.on('pong', heartbeat);

    console.log('PENGGUNA TERHUBUNG:', roomId);

    if (!clients.has(roomId)) {
        clients.set(roomId, new Set());
    }

    clients.get(roomId).add(ws);
    ws.user = null;

    ws.send(JSON.stringify({
        type: 'connected',
    }));

    ws.on('message', (message) => {
        try {
            const data = JSON.parse(message.toString());

            if (data.userId) {
                ws.user = {
                    userId: data.userId,
                    userName: data.userName,
                    userColor: data.userColor,
                };
            }

            const roomClients = clients.get(roomId);

            if (!roomClients) return;

            roomClients.forEach(client => {
                if (
                    client !== ws &&
                    client.readyState === WebSocket.OPEN
                ) {
                    client.send(JSON.stringify(data));
                }
            });
        } catch (err) {
            console.error('Gagal memproses pesan:', err);
        }
    });

    ws.on('close', () => {
        console.log('PENGGUNA TERPUTUS:', roomId);

        if (!clients.has(roomId)) return;

        clients.get(roomId).delete(ws);

        if (clients.get(roomId).size === 0) {
            clients.delete(roomId);
            return;
        }

        if (!ws.user) return;

        clients.get(roomId).forEach(client => {
            if (client.readyState === WebSocket.OPEN) {
                client.send(JSON.stringify({
                    type: 'leave',
                    ...ws.user,
                }));
            }
        });
    });
});

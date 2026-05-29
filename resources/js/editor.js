import 'quill/dist/quill.snow.css'
import 'quill-cursors/css'

import Quill from 'quill'
import QuillCursors from 'quill-cursors'

Quill.register('modules/cursors', QuillCursors)

document.addEventListener('DOMContentLoaded', () => {

    if (!window.editorConfig) return

    const config = window.editorConfig

<<<<<<< HEAD
=======
    // ── Quill Init ──────────────────────────────────────────────────────
>>>>>>> restore-old
    const quill = new Quill('#editor', {
        theme: 'snow',

        modules: {
            toolbar: '#toolbar',

            cursors: {
                hideDelayMs: 5000,
                hideSpeedMs: 0,
                selectionChangeSource: null,
            },
        },
    })

    const cursors = quill.getModule('cursors')

<<<<<<< HEAD
    const remoteCursorLayer = document.createElement('div')
    remoteCursorLayer.className = 'remote-cursors-layer'
=======
    // ── Remote cursor layer ─────────────────────────────────────────────
    const remoteCursorLayer = document.createElement('div')
    remoteCursorLayer.className = 'remote-cursors-layer'
    // Append to the ql-editor's parent so it shares the same coordinate system
>>>>>>> restore-old
    quill.root.parentNode.appendChild(remoteCursorLayer)

    let applyingRemoteChange = false
    let socket = null
    let reconnectTimeout = null
    let reconnectAttempts = 0
    const remoteUsers = new Map()
    const connectionBadge = document.getElementById('connection-badge')

<<<<<<< HEAD
=======
    // ── Helpers ─────────────────────────────────────────────────────────
>>>>>>> restore-old
    const normalizeRange = (range) => {
        if (!range) return null

        const maxIndex = Math.max(quill.getLength() - 1, 0)
        const index = Math.min(Math.max(Number(range.index) || 0, 0), maxIndex)
        const length = Math.min(Math.max(Number(range.length) || 0, 0), maxIndex - index)

        return { index, length }
    }

    const cursorSelector = (userId) => `[data-user-id="${String(userId).replace(/["\\]/g, '\\$&')}"]`

    const setConnectionStatus = (status) => {
        if (!connectionBadge) return

        const statuses = {
            connecting: {
                className: 'flex items-center gap-2 px-3 py-1.5 rounded-full text-xs bg-yellow-500/10 border border-yellow-500/20 text-yellow-400',
                dotClass: 'w-2 h-2 rounded-full bg-yellow-500 animate-pulse',
                label: 'Menghubungkan...',
            },
            connected: {
                className: 'flex items-center gap-2 px-3 py-1.5 rounded-full text-xs bg-emerald-500/10 border border-emerald-500/20 text-emerald-400',
                dotClass: 'w-2 h-2 rounded-full bg-emerald-500',
                label: 'Realtime terhubung',
            },
            disconnected: {
                className: 'flex items-center gap-2 px-3 py-1.5 rounded-full text-xs bg-red-500/10 border border-red-500/20 text-red-400',
                dotClass: 'w-2 h-2 rounded-full bg-red-500',
                label: 'Realtime terputus',
            },
        }

        const current = statuses[status]
        connectionBadge.className = current.className
        connectionBadge.innerHTML = `<div class="${current.dotClass}"></div>${current.label}`
    }

    const sendRealtimeMessage = (payload) => {
        if (!socket || socket.readyState !== WebSocket.OPEN) return false

        socket.send(JSON.stringify({
            ...payload,
            userId: config.userId,
            userName: config.userName,
            userColor: config.userColor,
        }))

        return true
    }

<<<<<<< HEAD
=======
    // ── Save Status Indicator ───────────────────────────────────────────
>>>>>>> restore-old
    const saveIndicator = document.getElementById('save-indicator')
    const saveText = document.getElementById('save-text')

    const setSaveStatus = (status) => {
        if (!saveIndicator || !saveText) return

        if (status === 'saving') {
            saveIndicator.className = 'w-2 h-2 rounded-full bg-yellow-500 animate-pulse'
            saveText.textContent = 'Menyimpan...'
        } else if (status === 'saved') {
            saveIndicator.className = 'w-2 h-2 rounded-full bg-emerald-500'
            saveText.textContent = 'Tersimpan'
        } else if (status === 'error') {
            saveIndicator.className = 'w-2 h-2 rounded-full bg-red-500'
            saveText.textContent = 'Gagal simpan'
        }
    }

<<<<<<< HEAD

=======
    // ── Remote Cursors ──────────────────────────────────────────────────
>>>>>>> restore-old
    const ensureRemoteCursor = (data) => {
        const userId = String(data.userId)
        const user = remoteUsers.get(userId)
        const color = data.userColor || user?.color || '#7c3aed'
        const name = data.userName || user?.name || 'Tamu'

        remoteUsers.set(userId, { ...user, name, color })

        try {
            cursors.createCursor(userId, name, color)
        } catch (err) {
            cursors.removeCursor(userId)
            cursors.createCursor(userId, name, color)
        }

        return userId
    }

    const renderRemoteCursor = (data) => {
        const userId = ensureRemoteCursor(data)
        const range = normalizeRange(data.range)

        if (!range) {
            removeRemoteCursor(userId)
            return
        }

        const user = remoteUsers.get(userId)

<<<<<<< HEAD
=======
        // getBounds returns coords relative to ql-editor, accounting for scroll
>>>>>>> restore-old
        const bounds = quill.getBounds(range.index + range.length)
        if (!bounds) return

        let cursor = remoteCursorLayer.querySelector(cursorSelector(userId))

        if (!cursor) {
            cursor = document.createElement('div')
            cursor.className = 'remote-cursor'
            cursor.dataset.userId = userId
            cursor.innerHTML = `
                <div class="remote-cursor-caret"></div>
                <div class="remote-cursor-flag">${user.name}</div>
            `
            remoteCursorLayer.appendChild(cursor)
        }

<<<<<<< HEAD
=======
        // Update position — CSS transition handles smooth movement
>>>>>>> restore-old
        cursor.style.transform = `translate(${bounds.left}px, ${bounds.top}px)`
        cursor.style.setProperty('--remote-cursor-color', user.color)
        cursor.querySelector('.remote-cursor-caret').style.height = `${Math.max(bounds.height, 18)}px`

<<<<<<< HEAD
=======
        // Update flag name text
>>>>>>> restore-old
        const flag = cursor.querySelector('.remote-cursor-flag')
        if (flag) {
            flag.textContent = user.name
            flag.style.background = user.color

<<<<<<< HEAD
=======
            // Show flag briefly then auto-hide
>>>>>>> restore-old
            flag.classList.add('remote-cursor-flag--visible')
            clearTimeout(cursor._flagTimer)
            cursor._flagTimer = setTimeout(() => {
                flag.classList.remove('remote-cursor-flag--visible')
            }, 2500)
        }
    }

    const renderRemotePointer = (data) => {
        const userId = ensureRemoteCursor(data)
        const user = remoteUsers.get(userId)
        let pointer = remoteCursorLayer.querySelector(cursorSelector(`pointer-${userId}`))

        if (!pointer) {
            pointer = document.createElement('div')
            pointer.className = 'remote-pointer'
            pointer.dataset.userId = `pointer-${userId}`
            pointer.innerHTML = `
                <svg class="remote-pointer-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M4 2l15 14-7 1.2L8.6 23 4 2z"></path>
                </svg>
                <div class="remote-pointer-label"></div>
            `
            remoteCursorLayer.appendChild(pointer)
        }

        pointer.style.transform = `translate(${Number(data.x) || 0}px, ${Number(data.y) || 0}px)`
        pointer.style.setProperty('--remote-cursor-color', user.color)
        pointer.querySelector('.remote-pointer-label').textContent = user.name

<<<<<<< HEAD
=======
        // Auto-hide pointer after inactivity
>>>>>>> restore-old
        clearTimeout(pointer._hideTimer)
        pointer.style.opacity = '1'
        pointer._hideTimer = setTimeout(() => {
            pointer.style.opacity = '0'
        }, 4000)
    }

    function removeRemoteCursor(userId) {
        remoteCursorLayer.querySelector(cursorSelector(userId))?.remove()
        remoteCursorLayer.querySelector(cursorSelector(`pointer-${userId}`))?.remove()
    }

    const updateRemoteCursorPositions = () => {
        remoteUsers.forEach((user, userId) => {
            if (!user.range) return

            renderRemoteCursor({
                userId,
                userName: user.name,
                userColor: user.color,
                range: user.range,
            })
        })
    }

    const sendCurrentCursor = () => {
        const range = quill.getSelection()

        if (!range) {
            return
        }

        sendRealtimeMessage({
            type: 'cursor',
            range,
        })
    }

<<<<<<< HEAD
=======
    // ── Load initial content ────────────────────────────────────────────
>>>>>>> restore-old
    if (config.content) {
        quill.root.innerHTML = config.content
    }

<<<<<<< HEAD
    const getRealtimeHost = () => {
        const hostname = window.location.hostname

        if (!hostname || hostname === 'localhost' || hostname === '::1' || hostname === '[::1]') {
            return '127.0.0.1'
        }

        return hostname
    }

=======
    // ── WebSocket Connection ────────────────────────────────────────────
>>>>>>> restore-old
    const connectRealtime = () => {
        clearTimeout(reconnectTimeout)
        setConnectionStatus('connecting')

        const protocol = window.location.protocol === 'https:' ? 'wss' : 'ws'
<<<<<<< HEAD
        const socketHost = getRealtimeHost()
=======
        const socketHost = '127.0.0.1'
>>>>>>> restore-old
        const socketUrl = `${protocol}://${socketHost}:6001/document.${config.documentUuid}`

        socket = new WebSocket(socketUrl)
        window.realtimeSocket = socket

        socket.addEventListener('open', () => {
            reconnectAttempts = 0
            setConnectionStatus('connected')
            sendCurrentCursor()
        })

        socket.addEventListener('message', (event) => {
            try {
                const data = JSON.parse(event.data)

                if (String(data.userId) === String(config.userId)) return

                if (data.type === 'connected') return

                if (data.type === 'typing') {
                    applyingRemoteChange = true
                    quill.updateContents(data.delta, 'api')
                    applyingRemoteChange = false

                    if (data.range) {
                        data.range = normalizeRange(data.range)
                        const userId = ensureRemoteCursor(data)
                        remoteUsers.get(userId).range = data.range
                        cursors.moveCursor(userId, data.range)
                        renderRemoteCursor(data)
                    }
                }

                if (data.type === 'cursor') {
                    if (!data.range) {
                        cursors.removeCursor(String(data.userId))
                        removeRemoteCursor(data.userId)
                        return
                    }

                    data.range = normalizeRange(data.range)
                    const userId = ensureRemoteCursor(data)
                    remoteUsers.get(userId).range = data.range
                    cursors.moveCursor(userId, data.range)
                    renderRemoteCursor(data)
                }

                if (data.type === 'pointer') {
                    renderRemotePointer(data)
                }

                if (data.type === 'leave') {
                    cursors.removeCursor(String(data.userId))
                    removeRemoteCursor(data.userId)
                    remoteUsers.delete(String(data.userId))
                }
            } catch (err) {
                console.error('Gagal membaca pesan realtime', err)
            }
        })

        socket.addEventListener('close', () => {
            setConnectionStatus('disconnected')
            socket = null

            const delay = Math.min(1000 * 2 ** reconnectAttempts, 10000)
            reconnectAttempts += 1
            reconnectTimeout = setTimeout(connectRealtime, delay)
        })

        socket.addEventListener('error', (err) => {
            console.error('Realtime bermasalah', err)
            if (socket && socket.readyState === WebSocket.OPEN) {
                socket.close()
            }
        })
    }

    connectRealtime()

<<<<<<< HEAD

=======
    // ── Editor Events ───────────────────────────────────────────────────
>>>>>>> restore-old
    quill.on('editor-change', () => {
        requestAnimationFrame(updateRemoteCursorPositions)
    })

    window.addEventListener('resize', updateRemoteCursorPositions)
    document.addEventListener('scroll', updateRemoteCursorPositions, true)

    quill.on('text-change', (delta, oldDelta, source) => {

        if (source !== 'user' || applyingRemoteChange) return

        sendRealtimeMessage({
            type: 'typing',
            delta,
            range: quill.getSelection() || null,
        })

        setTimeout(sendCurrentCursor, 0)

        autoSave()
    })

    let lastCursorWhisper = 0
    const CURSOR_THROTTLE_MS = 100

    quill.on('selection-change', (range, oldRange, source) => {

        if (source !== 'user') return

        const now = Date.now()

        if (now - lastCursorWhisper < CURSOR_THROTTLE_MS) return

        lastCursorWhisper = now

        sendRealtimeMessage({
            type: 'cursor',
            range: range || null,
        })
    })

    let lastPointerWhisper = 0
    const POINTER_THROTTLE_MS = 50

    quill.container.addEventListener('mousemove', (event) => {
        const now = Date.now()

        if (now - lastPointerWhisper < POINTER_THROTTLE_MS) return

        lastPointerWhisper = now

        const rect = quill.container.getBoundingClientRect()

        sendRealtimeMessage({
            type: 'pointer',
            x: event.clientX - rect.left,
            y: event.clientY - rect.top,
        })
    })

<<<<<<< HEAD
=======
    // ── Auto Save ───────────────────────────────────────────────────────
>>>>>>> restore-old
    let saveTimeout = null

    function autoSave() {

        clearTimeout(saveTimeout)

        setSaveStatus('saving')

        saveTimeout = setTimeout(() => {

            fetch(`/documents/${config.documentUuid}/save`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),
                },
                body: JSON.stringify({
                    content: quill.root.innerHTML,
                }),
            })
                .then(res => {
                    if (res.ok) {
                        setSaveStatus('saved')
                    } else {
                        setSaveStatus('error')
                    }
                })
                .catch(() => {
                    setSaveStatus('error')
                })

        }, 1000)
    }

<<<<<<< HEAD
=======
    // ── Document Title Rename ───────────────────────────────────────────
>>>>>>> restore-old
    const titleInput = document.getElementById('document-title')

    if (titleInput) {
        let originalTitle = titleInput.value

        const saveTitle = () => {
            const newTitle = titleInput.value.trim()
            if (!newTitle || newTitle === originalTitle) {
                titleInput.value = originalTitle
                return
            }

            originalTitle = newTitle
            document.title = `${newTitle} - Gdox`

            fetch(`/documents/${config.documentUuid}`, {
                method: 'PUT',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),
                },
                body: JSON.stringify({ title: newTitle }),
            })
        }

        titleInput.addEventListener('blur', saveTitle)
        titleInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault()
                titleInput.blur()
            }
        })
    }

<<<<<<< HEAD
=======
    // ── Share Button ────────────────────────────────────────────────────
>>>>>>> restore-old
    const shareBtn = document.getElementById('share-btn')
    const shareToast = document.getElementById('share-toast')

    if (shareBtn) {
        shareBtn.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(window.location.href)

                if (shareToast) {
                    shareToast.style.opacity = '1'
                    shareToast.style.transform = 'translate(-50%, 0)'

                    setTimeout(() => {
                        shareToast.style.opacity = '0'
                        shareToast.style.transform = 'translate(-50%, 10px)'
                    }, 2500)
                }
            } catch {
<<<<<<< HEAD
=======
                // Fallback for older browsers
>>>>>>> restore-old
                const textarea = document.createElement('textarea')
                textarea.value = window.location.href
                document.body.appendChild(textarea)
                textarea.select()
                document.execCommand('copy')
                textarea.remove()

                if (shareToast) {
                    shareToast.style.opacity = '1'
                    setTimeout(() => { shareToast.style.opacity = '0' }, 2500)
                }
            }
        })
    }

<<<<<<< HEAD
=======
    // ── Save Version Button ─────────────────────────────────────────────
>>>>>>> restore-old
    const saveVersionBtn = document.getElementById('save-version-btn')

    if (saveVersionBtn) {
        saveVersionBtn.addEventListener('click', () => {
            saveVersionBtn.disabled = true
            saveVersionBtn.style.opacity = '0.5'

            fetch(`/documents/${config.documentUuid}/versions`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),
                },
                body: JSON.stringify({
                    content: quill.root.innerHTML,
                }),
            })
                .then(res => res.json())
                .then(data => {
                    saveVersionBtn.disabled = false
                    saveVersionBtn.style.opacity = '1'

                    if (data.success) {
<<<<<<< HEAD
=======
                        // Show a brief toast
>>>>>>> restore-old
                        if (shareToast) {
                            shareToast.textContent = `Versi ${data.version_number} tersimpan!`
                            shareToast.style.opacity = '1'
                            shareToast.style.transform = 'translate(-50%, 0)'
                            setTimeout(() => {
                                shareToast.style.opacity = '0'
                                shareToast.style.transform = 'translate(-50%, 10px)'
                                shareToast.textContent = 'Tautan disalin!'
                            }, 2500)
                        }

<<<<<<< HEAD
=======
                        // Refresh sidebar if open
>>>>>>> restore-old
                        const sidebar = document.getElementById('version-sidebar')
                        if (sidebar && !sidebar.classList.contains('hidden')) {
                            loadVersions()
                        }
                    }
                })
                .catch(() => {
                    saveVersionBtn.disabled = false
                    saveVersionBtn.style.opacity = '1'
                })
        })
    }

<<<<<<< HEAD
=======
    // ── Version History Sidebar ─────────────────────────────────────────
>>>>>>> restore-old
    const versionHistoryBtn = document.getElementById('version-history-btn')
    const closeVersionsBtn = document.getElementById('close-versions-btn')
    const versionSidebar = document.getElementById('version-sidebar')
    const versionsList = document.getElementById('versions-list')

    function loadVersions() {
        if (!versionsList) return

        versionsList.innerHTML = '<div class="text-center text-gray-500 text-sm py-8">Memuat...</div>'

        fetch(`/documents/${config.documentUuid}/versions`, {
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'),
            },
        })
            .then(res => res.json())
            .then(versions => {
                if (!versions.length) {
                    versionsList.innerHTML = `
                        <div class="text-center text-gray-500 text-sm py-8">
                            <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Belum ada versi tersimpan.<br>Klik "Simpan Versi" untuk menyimpan versi pertama.
                        </div>
                    `
                    return
                }

                versionsList.innerHTML = versions.map(v => `
                    <div class="version-item" data-version-id="${v.id}">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-semibold text-white">Versi ${v.version_number}</span>
                            <button class="restore-version-btn text-xs px-2 py-1 rounded-md bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 border border-indigo-500/20 transition-colors" data-version-id="${v.id}">
                                Pulihkan
                            </button>
                        </div>
                        <div class="text-xs text-gray-400 mb-1">${v.saved_by} · ${v.created_at}</div>
                        <div class="text-xs text-gray-600 line-clamp-2">${v.content_preview}</div>
                    </div>
                `).join('')

<<<<<<< HEAD
=======
                // Attach restore handlers
>>>>>>> restore-old
                versionsList.querySelectorAll('.restore-version-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation()
                        const versionId = btn.dataset.versionId

                        if (!confirm('Pulihkan ke versi ini? Konten saat ini akan disimpan sebagai versi baru terlebih dahulu.')) return

                        btn.disabled = true
                        btn.textContent = 'Memulihkan...'

                        fetch(`/documents/${config.documentUuid}/versions/${versionId}/restore`, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                            },
                        })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    quill.root.innerHTML = data.content

<<<<<<< HEAD
=======
                                    // Broadcast the restored content to others
>>>>>>> restore-old
                                    sendRealtimeMessage({
                                        type: 'typing',
                                        delta: quill.getContents(),
                                        range: quill.getSelection(),
                                    })

                                    loadVersions()
                                }
                            })
                            .catch(() => {
                                btn.disabled = false
                                btn.textContent = 'Pulihkan'
                            })
                    })
                })
            })
            .catch(() => {
                versionsList.innerHTML = '<div class="text-center text-red-400 text-sm py-8">Gagal memuat versi.</div>'
            })
    }

    if (versionHistoryBtn && versionSidebar) {
        versionHistoryBtn.addEventListener('click', () => {
            versionSidebar.classList.toggle('hidden')

            if (!versionSidebar.classList.contains('hidden')) {
                loadVersions()
            }
        })
    }

    if (versionSidebar) {
        const url = new URL(window.location.href)
        if (url.searchParams.get('open_history') === '1') {
            versionSidebar.classList.remove('hidden')
            loadVersions()
        }
    }

    if (closeVersionsBtn && versionSidebar) {
        closeVersionsBtn.addEventListener('click', () => {
            versionSidebar.classList.add('hidden')
        })
    }
})

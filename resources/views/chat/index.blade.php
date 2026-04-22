<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                YachaPlanner — Asistente STEAM
            </h2>
            <span class="text-sm text-gray-500">
                Créditos disponibles: <strong id="credits-count">{{ auth()->user()->remainingCredits() }}</strong>
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-4 h-[calc(100vh-200px)]">

                {{-- Sidebar módulos --}}
                <div class="w-64 flex-shrink-0">
                    <div class="bg-white rounded-lg shadow p-4">
                        <h3 class="font-medium text-gray-700 mb-3 text-sm uppercase tracking-wide">Módulos</h3>
                        @foreach($modules as $module)
                        <button onclick="selectModule('{{ $module->slug }}', '{{ $module->name }}')"
                            id="btn-{{ $module->slug }}"
                            class="module-btn w-full text-left px-3 py-2 rounded-md text-sm mb-1 hover:bg-green-50 hover:text-green-700 transition-colors">
                            {{ $module->name }}
                        </button>
                        @endforeach
                    </div>

                    @if($sessions->count())
                    <div class="bg-white rounded-lg shadow p-4 mt-4">
                        <h3 class="font-medium text-gray-700 mb-3 text-sm uppercase tracking-wide">Recientes</h3>
                        @foreach($sessions as $session)
                        <button onclick="loadSession({{ $session->id }}, '{{ $session->module }}')"
                            class="w-full text-left px-3 py-2 rounded-md text-xs mb-1 hover:bg-gray-50 text-gray-600 truncate">
                            {{ $session->title ?? 'Sin título' }}
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Chat principal --}}
                <div class="flex-1 flex flex-col bg-white rounded-lg shadow overflow-hidden">

                    {{-- Header del chat --}}
                    <div class="px-4 py-3 border-b bg-gray-50 flex items-center justify-between">
                        <span id="current-module-name" class="text-sm font-medium text-gray-600">
                            Selecciona un módulo para comenzar
                        </span>
                        <button onclick="newChat()"
                            class="text-xs text-gray-400 hover:text-gray-600">
                            + Nueva sesión
                        </button>
                    </div>

                    {{-- Mensajes --}}
                    <div id="messages" class="flex-1 overflow-y-auto p-4 space-y-4">
                        <div id="welcome-msg" class="text-center text-gray-400 text-sm mt-8">
                            <p class="text-2xl mb-2">🌱</p>
                            <p>Selecciona un módulo y escribe tu solicitud.</p>
                            <p class="mt-1">Ejemplo: <em>"Programación bimestral de Ciencia y Tecnología para 4to de primaria"</em></p>
                        </div>
                    </div>

                    {{-- Input --}}
                    <div class="border-t p-4">
                        <div class="flex gap-2">
                            <textarea id="user-input"
                                placeholder="Escribe tu solicitud pedagógica..."
                                rows="2"
                                class="flex-1 resize-none rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-green-500"
                                onkeydown="handleKey(event)"></textarea>
                            <button onclick="sendMessage()"
                                id="send-btn"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 disabled:opacity-50 transition-colors">
                                Enviar
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">
                            Módulo activo: <span id="module-label">ninguno</span> ·
                            Enter + Shift para nueva línea · Enter para enviar
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentModule = null;
        let currentSessionId = null;

        function selectModule(slug, name) {
            currentModule = slug;
            currentSessionId = null;
            document.getElementById('current-module-name').textContent = name;
            document.getElementById('module-label').textContent = name;
            document.querySelectorAll('.module-btn').forEach(b => {
                b.classList.remove('bg-green-100', 'text-green-700', 'font-medium');
            });
            document.getElementById('btn-' + slug).classList.add('bg-green-100', 'text-green-700', 'font-medium');
            document.getElementById('messages').innerHTML = `
                <div class="text-center text-gray-400 text-sm mt-4">
                    <p>Módulo <strong>${name}</strong> activado. ¿Qué necesitas crear?</p>
                </div>`;
            document.getElementById('user-input').focus();
        }

        function newChat() {
            currentSessionId = null;
            document.getElementById('messages').innerHTML = '';
        }

        function loadSession(id, module) {
            currentSessionId = id;
            currentModule = module;
        }

        function handleKey(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        }

        function addMessage(role, content) {
            const el = document.getElementById('welcome-msg');
            if (el) el.remove();

            const div = document.createElement('div');
            div.className = role === 'user'
                ? 'flex justify-end'
                : 'flex justify-start';

            const bubble = document.createElement('div');
            bubble.className = role === 'user'
                ? 'max-w-[75%] bg-green-600 text-white rounded-2xl rounded-tr-sm px-4 py-2 text-sm'
                : 'max-w-[80%] bg-gray-100 text-gray-800 rounded-2xl rounded-tl-sm px-4 py-3 text-sm prose prose-sm';

            if (role === 'assistant') {
                bubble.innerHTML = marked.parse(content);
            } else {
                bubble.textContent = content;
            }

            div.appendChild(bubble);
            document.getElementById('messages').appendChild(div);
            document.getElementById('messages').scrollTop = 99999;
        }

        function showTyping() {
            const div = document.createElement('div');
            div.id = 'typing';
            div.className = 'flex justify-start';
            div.innerHTML = '<div class="bg-gray-100 rounded-2xl rounded-tl-sm px-4 py-3"><span class="text-gray-400 text-sm">Generando...</span></div>';
            document.getElementById('messages').appendChild(div);
            document.getElementById('messages').scrollTop = 99999;
        }

        function removeTyping() {
            const t = document.getElementById('typing');
            if (t) t.remove();
        }

        async function sendMessage() {
            const input = document.getElementById('user-input');
            const text = input.value.trim();
            if (!text || !currentModule) {
                if (!currentModule) alert('Selecciona un módulo primero');
                return;
            }

            input.value = '';
            document.getElementById('send-btn').disabled = true;
            addMessage('user', text);
            showTyping();

            try {
                const res = await fetch('{{ route("chat.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        module: currentModule,
                        message: text,
                        session_id: currentSessionId,
                    })
                });

                const data = await res.json();
                removeTyping();

                if (data.error) {
                    addMessage('assistant', '❌ ' + data.error);
                } else {
                    addMessage('assistant', data.reply);
                    currentSessionId = data.session_id;
                    document.getElementById('credits-count').textContent = data.credits_remaining;
                }
            } catch(e) {
                removeTyping();
                addMessage('assistant', '❌ Error de conexión. Intenta de nuevo.');
            }

            document.getElementById('send-btn').disabled = false;
            input.focus();
        }
    </script>

    {{-- Marked.js para renderizar markdown --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/9.1.6/marked.min.js"></script>
</x-app-layout>
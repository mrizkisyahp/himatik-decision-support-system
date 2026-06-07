@props(['endpoint' => url('/api/chatbot')])

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<script>
    window.himatikChatbot = function () {
        return {
            open: false,
            input: '',
            loading: false,
            messages: [
                {
                    role: 'bot',
                    text: 'Halo, saya bisa bantu menjelaskan alur rekrutmen, departemen/biro, jadwal wawancara, pengumuman, dan konsep SPK HIMATIK PNJ.'
                }
            ],
            async send() {
                const message = this.input.trim();

                if (!message || this.loading) {
                    return;
                }

                this.messages.push({ role: 'user', text: message });
                this.input = '';
                this.loading = true;
                this.scrollToBottom();

                try {
                    const response = await fetch(@json($endpoint), {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            message,
                            page: window.location.pathname,
                        }),
                    });

                    const payload = await response.json().catch(() => ({}));
                    const reply = response.ok
                        ? payload.reply
                        : (payload.message || 'Maaf, pertanyaan itu tidak bisa saya jawab.');

                    this.messages.push({
                        role: 'bot',
                        text: reply || 'Maaf, jawaban belum tersedia.'
                    });
                } catch (error) {
                    this.messages.push({
                        role: 'bot',
                        text: 'Maaf, chatbot belum bisa dihubungi. Coba lagi nanti.'
                    });
                } finally {
                    this.loading = false;
                    this.scrollToBottom();
                }
            },
            scrollToBottom() {
                this.$nextTick(() => {
                    const panel = this.$refs.messages;

                    if (panel) {
                        panel.scrollTop = panel.scrollHeight;
                    }
                });
            }
        };
    };
</script>

<div x-data="himatikChatbot()" x-cloak class="fixed bottom-5 right-5 z-[80]">
    <div
        x-show="open"
        x-transition.origin.bottom.right
        class="mb-4 flex h-[min(34rem,calc(100vh-7rem))] w-[calc(100vw-2.5rem)] max-w-sm flex-col overflow-hidden rounded-[1.75rem] border border-white/30 bg-white shadow-2xl shadow-[#223872]/25 sm:w-96"
    >
        <div class="flex items-center justify-between bg-[#223872] px-5 py-4 text-white">
            <div>
                <p class="text-sm font-extrabold">Asisten HIMATIK</p>
                <p class="text-xs text-white/70">Info rekrutmen dan SPK</p>
            </div>
            <button
                type="button"
                class="rounded-full p-2 text-white/80 transition hover:bg-white/10 hover:text-white"
                aria-label="Tutup chatbot"
                @click="open = false"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div x-ref="messages" class="flex-1 space-y-3 overflow-y-auto bg-[#F4F7FF] px-4 py-4">
            <template x-for="(message, index) in messages" :key="index">
                <div class="flex" :class="message.role === 'user' ? 'justify-end' : 'justify-start'">
                    <div
                        class="max-w-[82%] rounded-2xl px-4 py-3 text-sm leading-6 shadow-sm"
                        :class="message.role === 'user'
                            ? 'bg-[#223872] text-white'
                            : 'border border-[#dbe5ff] bg-white text-[#333333]'"
                        x-text="message.text"
                    ></div>
                </div>
            </template>

            <div x-show="loading" class="flex justify-start">
                <div class="rounded-2xl border border-[#dbe5ff] bg-white px-4 py-3 text-sm text-[#64748b] shadow-sm">
                    Mengetik...
                </div>
            </div>
        </div>

        <form class="border-t border-[#dbe5ff] bg-white p-3" @submit.prevent="send">
            <div class="flex items-end gap-2">
                <textarea
                    x-model="input"
                    rows="1"
                    maxlength="1000"
                    class="max-h-28 min-h-11 flex-1 resize-none rounded-2xl border border-[#c8d3ea] bg-white px-4 py-3 text-sm text-[#333333] outline-none transition placeholder:text-[#94a3b8] focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15"
                    placeholder="Tanya tentang rekrutmen..."
                    @keydown.enter.prevent="send"
                ></textarea>
                <button
                    type="submit"
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#223872] text-white shadow-lg shadow-[#223872]/20 transition hover:bg-[#1a2c5b] disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="loading || !input.trim()"
                    aria-label="Kirim pertanyaan"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m22 2-7 20-4-9-9-4Z" />
                        <path d="M22 2 11 13" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <button
        type="button"
        class="flex h-14 w-14 items-center justify-center rounded-full bg-[#223872] text-white shadow-2xl shadow-[#223872]/30 transition hover:-translate-y-0.5 hover:bg-[#1a2c5b] focus:outline-none focus:ring-4 focus:ring-[#4A90E2]/30"
        aria-label="Buka chatbot HIMATIK"
        @click="open = !open"
    >
        <svg x-show="!open" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z" />
        </svg>
        <svg x-show="open" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 6 6 18" />
            <path d="m6 6 12 12" />
        </svg>
    </button>
</div>

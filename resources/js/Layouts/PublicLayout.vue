<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    auth: Object,
});
</script>

<template>
    <div class="min-h-screen bg-slate-950 font-sans text-slate-200 selection:bg-indigo-500 selection:text-white">
        <!-- Background Decor -->
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute -top-[10%] -left-[10%] h-[40%] w-[40%] rounded-full bg-indigo-900/20 blur-[120px]"></div>
            <div class="absolute top-[20%] -right-[5%] h-[30%] w-[30%] rounded-full bg-blue-900/10 blur-[100px]"></div>
        </div>

        <!-- Navigation -->
        <nav class="sticky top-0 z-50 border-b border-white/5 bg-slate-950/80 backdrop-blur-md">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-20 items-center justify-between">
                    <!-- Logo -->
                    <Link :href="route('home')" class="flex items-center gap-2 group">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 font-bold text-white shadow-lg shadow-indigo-600/30 group-hover:scale-105 transition-transform">
                            SG
                        </div>
                        <span class="text-xl font-black tracking-tight text-white font-outfit">RESERVAS</span>
                    </Link>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex items-center gap-8">
                        <Link :href="route('home')" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">Destinos</Link>
                        <a href="#" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">Ofertas</a>
                        <a href="#" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">Ajuda</a>
                    </div>

                    <!-- Auth Actions -->
                    <div class="flex items-center gap-4">
                        <template v-if="auth.user">
                            <Link :href="route('dashboard')" class="text-sm font-medium text-slate-300 hover:text-white">Dashboard</Link>
                        </template>
                        <template v-else>
                            <Link :href="route('login')" class="text-sm font-medium text-slate-300 hover:text-white">Entrar</Link>
                            <Link :href="route('register')" class="rounded-full bg-white px-6 py-2.5 text-sm font-bold text-slate-900 shadow-xl shadow-white/10 hover:bg-slate-100 transition-all hover:scale-105 active:scale-95">
                                Cadastrar
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            <!-- Flash Messages -->
            <div v-if="$page.props.flash?.success" class="max-w-7xl mx-auto px-4 mt-6">
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-6 rounded-3xl flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500">
                    <span class="text-2xl">🎉</span>
                    <span class="font-bold">{{ $page.props.flash.success }}</span>
                </div>
            </div>
            <div v-if="$page.props.errors?.error" class="max-w-7xl mx-auto px-4 mt-6">
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-6 rounded-3xl flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500">
                    <span class="text-2xl">⚠️</span>
                    <span class="font-bold">{{ $page.props.errors.error }}</span>
                </div>
            </div>

            <slot />
        </main>

        <!-- Footer -->
        <footer class="border-t border-white/5 py-12 mt-20">
            <div class="mx-auto max-w-7xl px-6 flex flex-col md:flex-row justify-between items-center gap-8 text-slate-500 text-sm">
                <div>© 2026 SG Reservas. Desenvolvido com Antigravity.</div>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white">Privacidade</a>
                    <a href="#" class="hover:text-white">Termos</a>
                    <a href="#" class="hover:text-white">Contato</a>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap');

.font-outfit {
    font-family: 'Outfit', sans-serif;
}
</style>

<script setup>
import { Link, usePage, router, Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import Button from 'primevue/button';
import Avatar from 'primevue/avatar';
import Menu from 'primevue/menu';

const props = defineProps({
    title: String
});

const page = usePage();
const user = computed(() => page.props.auth.user);

// Menu do usuário (Topbar)
const userMenuRef = ref(null);
const userMenuItems = ref([
    {
        label: 'Perfil',
        icon: 'pi pi-user',
        command: () => {
            router.get(route('profile.edit'));
        }
    },
    {
        separator: true
    },
    {
        label: 'Sair',
        icon: 'pi pi-sign-out',
        command: () => {
            router.post(route('logout'));
        }
    }
]);

const toggleUserMenu = (event) => {
    userMenuRef.value.toggle(event);
};

const showMobileSidebar = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gray-100 font-sans antialiased flex flex-col h-screen">
        <Head :title="title" />

        <!-- TOPBAR -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-40 shrink-0">
            <!-- Logo (Esquerda) -->
            <div class="flex items-center gap-4">
                <button @click="showMobileSidebar = !showMobileSidebar" class="lg:hidden text-gray-500 focus:outline-none">
                    <i class="pi pi-bars text-xl"></i>
                </button>
                <Link :href="route('dashboard')" class="flex items-center gap-3">
                    <div class="h-9 w-9 bg-blue-600 rounded-lg flex items-center justify-center shadow-sm">
                        <i class="pi pi-home text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-black text-gray-900 tracking-tighter hidden sm:block">CORRETORES</span>
                </Link>
            </div>

            <!-- Título da Página (Direita) -->
            <div class="flex items-center gap-6">
                <div v-if="title" class="hidden md:flex items-center">
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-sm font-semibold rounded-full border border-gray-200">
                        {{ title }}
                    </span>
                </div>
                
                <div class="flex items-center gap-3 border-l pl-6 border-gray-100">
                    <div class="flex flex-col items-end hidden sm:flex">
                        <span class="text-sm font-bold text-gray-800">{{ user.name }}</span>
                        <span class="text-[10px] uppercase font-bold text-blue-500 tracking-widest">
                            {{ typeof user.roles?.[0] === 'string' ? user.roles[0] : (user.roles?.[0]?.name || 'Usuário') }}
                        </span>
                    </div>
                    <Button icon="pi pi-user" rounded text aria-haspopup="true" @click="toggleUserMenu" class="bg-gray-50" />
                    <Menu ref="userMenuRef" id="user_palette_menu" :model="userMenuItems" :popup="true" />
                </div>
            </div>
        </header>

        <div class="flex flex-1 overflow-hidden">
            <!-- SIDEBAR -->
            <aside class="fixed top-16 bottom-0 left-0 z-30 w-64 bg-white border-r border-gray-200 transition-transform transform lg:static lg:translate-x-0"
                   :class="showMobileSidebar ? 'translate-x-0' : '-translate-x-full'">
                <div class="flex h-full flex-col">
                    <div class="flex-1 overflow-y-auto pt-4">
                        <Sidebar />
                    </div>
                    <div class="border-t p-4 flex items-center justify-between text-xs text-gray-400">
                        <span>v1.2.0</span>
                        <div class="flex gap-2">
                             <i class="pi pi-circle-fill text-green-500 text-[8px]"></i>
                             <span>Online</span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Overlay Mobile -->
            <div v-if="showMobileSidebar" @click="showMobileSidebar = false" class="fixed inset-0 z-20 bg-black/40 backdrop-blur-sm lg:hidden"></div>

            <!-- CONTEÚDO PRINCIPAL -->
            <main class="flex-1 overflow-y-auto bg-gray-50/50 p-4 sm:p-6 lg:p-8">
                <!-- Mobile Title -->
                <h1 v-if="title" class="text-2xl font-bold text-gray-800 mb-6 md:hidden">
                    {{ title }}
                </h1>
                
                <div class="max-w-7xl mx-auto">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
.p-sidebar .p-sidebar-content {
    padding: 0;
}
</style>

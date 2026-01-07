<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import Menu from 'primevue/menu';
import { ref, computed } from 'vue';

const page = usePage();
const userPermissions = computed(() => page.props.auth.user?.can || []);

const rawItems = [
    {
        label: 'Geral',
        items: [
            {
                label: 'Dashboard',
                icon: 'pi pi-home',
                route: 'dashboard',
                permission: 'access_dashboard'
            }
        ]
    },
    {
        label: 'Cadastro',
        items: [
            {
                label: 'Usuários',
                icon: 'pi pi-users',
                route: 'users.index',
                permission: 'view_users'
            }
        ]
    },
    {
        label: 'Configurações',
        items: [
            {
                label: 'Papéis (Perfis)',
                icon: 'pi pi-shield',
                route: 'roles.index',
                permission: 'manage_roles'
            },
            {
                label: 'Permissões',
                icon: 'pi pi-lock',
                route: 'permissions.index',
                permission: 'manage_permissions'
            }
        ]
    }
];

const items = computed(() => {
    return rawItems.map(group => {
        const filteredItems = group.items.filter(item => {
            return !item.permission || userPermissions.value.includes(item.permission);
        });

        if (filteredItems.length > 0) {
            return { ...group, items: filteredItems };
        }
        return null;
    }).filter(group => group !== null);
});
</script>

<template>
    <div class="px-2">
        <Menu :model="items" class="w-full border-none bg-transparent">
            <template #item="{ item }">
                <Link v-if="item.route" :href="route(item.route)"
                      class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors group"
                      :class="{ 'bg-blue-50 text-blue-600 font-semibold': route().current(item.route) }">
                    <i :class="[item.icon, 'text-lg mr-3 group-hover:text-blue-600', route().current(item.route) ? 'text-blue-600' : 'text-gray-400']" />
                    <span class="text-sm">{{ item.label }}</span>
                </Link>
                <div v-else class="px-4 py-2 mt-4 text-[11px] uppercase font-bold text-gray-400 tracking-wider">
                    {{ item.label }}
                </div>
            </template>
        </Menu>
    </div>
</template>

<style scoped>
:deep(.p-menu) {
    background: transparent;
    border: none;
    padding: 0;
}
:deep(.p-menu-list) {
    padding: 0;
}
:deep(.p-submenu-header) {
    background: transparent;
    padding: 0;
}
</style>

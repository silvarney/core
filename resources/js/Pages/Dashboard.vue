<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const can = computed(() => page.props.auth.user?.can || []);

// Helper to check permission
const hasPermission = (permission) => can.value.includes(permission);
</script>

<template>
    <AppLayout title="Dashboard">

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <!-- Stats Cards -->
            <div v-if="hasPermission('view_users')" class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3 md:gap-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                    <i class="pi pi-users text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Usuários</p>
                    <p class="text-2xl font-bold text-gray-800">12</p>
                </div>
            </div>

            <div v-if="hasPermission('manage_roles')" class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3 md:gap-4">
                <div class="p-3 bg-green-50 text-green-600 rounded-lg">
                    <i class="pi pi-shield text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Papéis</p>
                    <p class="text-2xl font-bold text-gray-800">4</p>
                </div>
            </div>
            
            <div v-if="hasPermission('manage_permissions')" class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3 md:gap-4">
                <div class="p-3 bg-orange-50 text-orange-600 rounded-lg">
                    <i class="pi pi-lock text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Permissões</p>
                    <p class="text-2xl font-bold text-gray-800">18</p>
                </div>
            </div>

            <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3 md:gap-4">
                <div class="p-3 bg-purple-50 text-purple-600 rounded-lg">
                    <i class="pi pi-check-circle text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Status</p>
                    <p class="text-2xl font-bold text-gray-800">Ativo</p>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-white p-8 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Bem-vindo ao Sistema de Corretores</h3>
            <p class="text-gray-600 leading-relaxed">
                Este é o seu painel de controle. Use o menu lateral para navegar entre as entidades do sistema.
                <span v-if="hasPermission('view_users') || hasPermission('manage_roles') || hasPermission('manage_permissions')">
                    Você pode gerenciar usuários, definir papéis e permissões granulares para garantir a segurança dos dados.
                </span>
            </p>
        </div>
    </AppLayout>
</template>

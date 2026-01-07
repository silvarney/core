<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import Tag from 'primevue/tag';
import InputText from 'primevue/inputtext';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ 
    users: Array,
    roles: Array
});

const filters = ref({
    global: { value: null }
});

const displayRoleModal = ref(false);
const displayEditModal = ref(false);
const editMode = ref(false);
const selectedUser = ref(null);

const roleForm = useForm({
    role: ''
});

const userForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
});

const openNew = () => {
    editMode.value = false;
    selectedUser.value = null;
    userForm.reset();
    displayEditModal.value = true;
};

const openEditRole = (user) => {
    selectedUser.value = user;
    roleForm.role = user.roles && user.roles.length > 0 ? user.roles[0] : '';
    displayRoleModal.value = true;
};

const openEditUser = (user) => {
    editMode.value = true;
    selectedUser.value = user;
    userForm.name = user.name;
    userForm.email = user.email;
    userForm.password = '';
    userForm.password_confirmation = '';
    displayEditModal.value = true;
};

const saveRole = () => {
    roleForm.patch(route('users.update-role', selectedUser.value.id), {
        onSuccess: () => {
            displayRoleModal.value = false;
            roleForm.reset();
        }
    });
};

const saveUser = () => {
    if (editMode.value) {
        userForm.patch(route('users.update', selectedUser.value.id), {
            onSuccess: () => {
                displayEditModal.value = false;
                userForm.reset();
            }
        });
    } else {
        userForm.post(route('users.store'), {
            onSuccess: () => {
                displayEditModal.value = false;
                userForm.reset();
            }
        });
    }
};

const deleteUser = (user) => {
    if (confirm(`Tem certeza que deseja desativar o usuário ${user.name}?`)) {
        // Logica de delete aqui se houver rota
    }
}
</script>

<template>
    <AppLayout title="Usuários">
        <div class="mb-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Gerenciar Usuários</h1>
            <Button label="Novo Usuário" icon="pi pi-user-plus" severity="primary" @click="openNew" />
        </div>
        
        <div class="card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <DataTable 
                v-model:filters="filters"
                :value="users" 
                paginator 
                :rows="10" 
                dataKey="id" 
                filterDisplay="menu"
                :globalFilterFields="['name', 'email']"
                class="p-datatable-sm"
                tableStyle="min-width: 50rem"
            >
                <template #header>
                    <div class="flex flex-wrap items-center justify-between gap-4 py-2">
                        <div class="relative">
                            <i class="pi pi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                            <InputText v-model="filters['global'].value" placeholder="Pesquisar usuários..." class="w-full sm:w-80 pl-10" />
                        </div>
                        <span class="text-xs uppercase font-bold text-gray-400 tracking-widest hidden sm:block">Total: {{ users.length }}</span>
                    </div>
                </template>

                <template #empty> Nenhum usuário encontrado. </template>

                <Column field="id" header="ID" style="width: 5rem" sortable class="text-gray-500 font-mono text-xs"></Column>
                
                <Column field="name" header="Nome" sortable>
                    <template #body="{ data }">
                        <div class="flex items-center gap-2">
                            <Avatar :label="data.name.charAt(0)" shape="circle" class="bg-blue-100 text-blue-700 font-bold" />
                            <span class="font-medium text-gray-800">{{ data.name }}</span>
                        </div>
                    </template>
                </Column>

                <Column field="email" header="E-mail" sortable class="text-gray-600"></Column>
                
                <Column header="Papel (Perfil)">
                    <template #body="{ data }">
                        <div class="flex flex-wrap gap-1">
                            <Tag v-for="role in data.roles" :key="role" :value="role" severity="success" rounded class="text-[10px] px-2" />
                            <span v-if="!data.roles || data.roles.length === 0" class="text-gray-400 text-xs italic">Sem papel definido</span>
                        </div>
                    </template>
                </Column>

                <Column header="Ações" style="width: 10rem">
                    <template #body="{ data }">
                        <div class="flex items-center gap-1">
                            <Button icon="pi pi-user-edit" rounded text severity="info" v-tooltip="'Alterar Papel'" @click="openEditRole(data)" />
                            <Button icon="pi pi-pencil" rounded text severity="secondary" v-tooltip="'Editar Dados'" @click="openEditUser(data)" />
                            <Button icon="pi pi-trash" rounded text severity="danger" v-tooltip="'Excluir'" @click="deleteUser(data)" />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Dialog Alterar Papel -->
        <Dialog v-model:visible="displayRoleModal" header="Alterar Papel do Usuário" :style="{ width: '400px' }" modal :draggable="false">
            <div class="flex flex-col gap-4 pt-2">
                <div v-if="selectedUser" class="p-3 bg-gray-50 rounded-lg border border-gray-100 italic text-sm">
                    <span class="font-bold not-italic">Usuário:</span> {{ selectedUser.name }}
                </div>
                <div class="flex flex-col gap-2">
                    <label for="role" class="text-sm font-medium text-gray-700">Selecione o Novo Papel</label>
                    <Dropdown id="role" v-model="roleForm.role" :options="roles" optionLabel="name" optionValue="name" 
                              placeholder="Selecione um papel" class="w-full" :class="{ 'p-invalid': roleForm.errors.role }" />
                    <small class="p-error" v-if="roleForm.errors.role">{{ roleForm.errors.role }}</small>
                </div>
            </div>
            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text severity="secondary" @click="displayRoleModal = false" />
                <Button label="Salvar Alterações" icon="pi pi-check" @click="saveRole" :loading="roleForm.processing" />
            </template>
        </Dialog>

        <!-- Dialog Editar Usuário -->
        <Dialog v-model:visible="displayEditModal" :header="editMode ? 'Editar Dados do Usuário' : 'Novo Usuário'" :style="{ width: '450px' }" modal :draggable="false">
            <div class="flex flex-col gap-5 pt-2">
                <div class="flex flex-col gap-2">
                    <label for="name" class="text-sm font-medium text-gray-700">Nome Completo</label>
                    <InputText id="name" v-model="userForm.name" :class="{ 'p-invalid': userForm.errors.name }" placeholder="Nome do usuário" />
                    <small class="p-error" v-if="userForm.errors.name">{{ userForm.errors.name }}</small>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="email" class="text-sm font-medium text-gray-700">E-mail</label>
                    <InputText id="email" v-model="userForm.email" :class="{ 'p-invalid': userForm.errors.email }" placeholder="email@exemplo.com" />
                    <small class="p-error" v-if="userForm.errors.email">{{ userForm.errors.email }}</small>
                </div>

                <div class="border-t border-gray-100 pt-3 mt-1">
                    <div class="flex items-center gap-2 mb-3 text-blue-600">
                        <i class="pi pi-lock text-sm" />
                        <span class="text-xs font-bold uppercase tracking-wider">Alterar Senha (Opcional)</span>
                    </div>
                    
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-2">
                            <label for="password" class="text-sm font-medium text-gray-700">Nova Senha</label>
                            <InputText id="password" v-model="userForm.password" type="password" :class="{ 'p-invalid': userForm.errors.password }" placeholder="Mínimo 8 caracteres" />
                            <small class="p-error" v-if="userForm.errors.password">{{ userForm.errors.password }}</small>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="password_confirmation" class="text-sm font-medium text-gray-700">Confirmar Nova Senha</label>
                            <InputText id="password_confirmation" v-model="userForm.password_confirmation" type="password" placeholder="Repita a nova senha" />
                        </div>
                    </div>
                </div>
            </div>
            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text severity="secondary" @click="displayEditModal = false" />
                <Button label="Salvar Alterações" icon="pi pi-check" @click="saveUser" :loading="userForm.processing" />
            </template>
        </Dialog>
    </AppLayout>
</template>

<style scoped>
:deep(.p-datatable-header) {
    background: transparent;
    border: none;
    padding: 1.25rem 1rem;
}
:deep(.p-datatable-thead > tr > th) {
    background: #f9fafb;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6b7280;
    padding: 1rem;
}
:deep(.p-datatable-tbody > tr > td) {
    padding: 1rem;
}
</style>

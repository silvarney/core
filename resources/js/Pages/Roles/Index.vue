<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import MultiSelect from 'primevue/multiselect';
import Tag from 'primevue/tag';

const props = defineProps({
    roles: Array,
    permissions: Array
});

const filters = ref({
    global: { value: null }
});

const displayModal = ref(false);
const editMode = ref(false);
const form = useForm({
    id: null,
    name: '',
    permissions: []
});

const openNew = () => {
    form.reset();
    editMode.value = false;
    displayModal.value = true;
};

const editRole = (role) => {
    form.id = role.id;
    form.name = role.name;
    form.permissions = role.permissions.map(p => p.name);
    editMode.value = true;
    displayModal.value = true;
};

const saveRole = () => {
    if (editMode.value) {
        form.put(route('roles.update', form.id), {
            onSuccess: () => closeModal()
        });
    } else {
        form.post(route('roles.store'), {
            onSuccess: () => closeModal()
        });
    }
};

const deleteRole = (role) => {
    if (confirm(`Tem certeza que deseja excluir o papel ${role.name}?`)) {
        form.delete(route('roles.destroy', role.id));
    }
};

const closeModal = () => {
    displayModal.value = false;
    form.reset();
};
</script>

<template>
    <AppLayout title="Papéis e Perfis">
        <div class="mb-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Gerenciar Papéis</h1>
            <Button label="Criar Novo Papel" icon="pi pi-plus" severity="primary" @click="openNew" />
        </div>
        
        <div class="card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <DataTable 
                v-model:filters="filters"
                :value="roles" 
                paginator 
                :rows="10" 
                dataKey="id"
                :globalFilterFields="['name']"
                class="p-datatable-sm"
                tableStyle="min-width: 50rem"
            >
                <template #header>
                    <div class="flex flex-wrap items-center justify-between gap-4 py-2">
                        <div class="relative">
                            <i class="pi pi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                            <InputText v-model="filters['global'].value" placeholder="Pesquisar papéis..." class="w-full sm:w-80 pl-10" />
                        </div>
                        <span class="text-xs uppercase font-bold text-gray-400 tracking-widest hidden sm:block">Total: {{ roles.length }}</span>
                    </div>
                </template>

                <template #empty> Nenhum papel encontrado. </template>

                <Column field="id" header="ID" style="width: 5rem" sortable class="text-gray-500 font-mono text-xs"></Column>
                
                <Column field="name" header="Nome do Papel" sortable>
                    <template #body="{ data }">
                        <span class="font-semibold text-gray-800">{{ data.name }}</span>
                    </template>
                </Column>
                
                <Column header="Permissões Associadas">
                    <template #body="{ data }">
                        <div class="flex flex-wrap gap-1">
                            <Tag v-for="p in data.permissions" :key="p.id" :value="p.name" severity="info" rounded class="text-[10px] px-2 capitalize bg-slate-100 text-slate-600 border-none" />
                            <span v-if="!data.permissions || data.permissions.length === 0" class="text-gray-400 text-xs italic">Sem permissões especializadas</span>
                        </div>
                    </template>
                </Column>

                <Column header="Ações" style="width: 8rem">
                    <template #body="{ data }">
                        <div class="flex gap-1">
                            <Button icon="pi pi-pencil" rounded text severity="secondary" v-tooltip="'Editar Papel'" @click="editRole(data)" />
                            <Button icon="pi pi-trash" rounded text severity="danger" v-tooltip="'Excluir'" @click="deleteRole(data)" 
                                    v-if="data.name !== 'Super Admin'" />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <Dialog v-model:visible="displayModal" :header="editMode ? 'Editar Papel' : 'Novo Papel'" :style="{ width: '450px' }" modal :draggable="false">
            <div class="flex flex-col gap-5 pt-2">
                <div class="flex flex-col gap-2">
                    <label for="name" class="text-sm font-medium text-gray-700">Nome do Papel</label>
                    <InputText id="name" v-model="form.name" :class="{ 'p-invalid': form.errors.name }" placeholder="Ex: Gerente, Vendedor..." />
                    <small class="p-error" v-if="form.errors.name">{{ form.errors.name }}</small>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Permissões</label>
                    <MultiSelect v-model="form.permissions" :options="permissions" optionLabel="name" optionValue="name" 
                                 placeholder="Selecione as permissões" :maxSelectedLabels="3" class="w-full" :filter="true" />
                    <small class="p-error" v-if="form.errors.permissions">{{ form.errors.permissions }}</small>
                </div>
            </div>
            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text severity="secondary" @click="closeModal" />
                <Button label="Gravar" icon="pi pi-check" @click="saveRole" :loading="form.processing" />
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
</style>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import Tag from 'primevue/tag';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    permissions: Array
});

const filters = ref({
    global: { value: null }
});

const displayModal = ref(false);
const editMode = ref(false);
const currentPermissionId = ref(null);

const form = useForm({
    name: '',
});

const openNew = () => {
    editMode.value = false;
    form.reset();
    displayModal.value = true;
};

const editPermission = (permission) => {
    editMode.value = true;
    currentPermissionId.value = permission.id;
    form.name = permission.name;
    displayModal.value = true;
};

const submitForm = () => {
    if (editMode.value) {
        form.put(route('permissions.update', currentPermissionId.value), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('permissions.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

const deletePermission = (permission) => {
    if (confirm(`Deseja realmente excluir a permissão "${permission.name}"?`)) {
        useForm({}).delete(route('permissions.destroy', permission.id));
    }
};

const closeModal = () => {
    displayModal.value = false;
    form.reset();
};
</script>

<template>
    <AppLayout title="Permissões do Sistema">
        
        <div class="mb-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Gerenciar Permissões</h1>
            <Button label="Nova Permissão" icon="pi pi-plus" severity="primary" @click="openNew" />
        </div>

        <div class="card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <DataTable 
                v-model:filters="filters"
                :value="permissions" 
                paginator 
                :rows="12" 
                dataKey="id"
                :globalFilterFields="['name']"
                class="p-datatable-sm"
                tableStyle="min-width: 50rem"
            >
                <template #header>
                    <div class="flex flex-wrap items-center justify-between gap-4 py-2">
                        <div class="relative">
                            <i class="pi pi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                            <InputText v-model="filters['global'].value" placeholder="Filtrar permissões..." class="w-full sm:w-80 pl-10" />
                        </div>
                        <span class="text-xs uppercase font-bold text-gray-400 tracking-widest hidden sm:block">Total: {{ permissions.length }}</span>
                    </div>
                </template>

                <template #empty> Nenhuma permissão encontrada. </template>

                <Column field="id" header="ID" style="width: 5rem" sortable class="text-gray-500 font-mono text-xs"></Column>
                
                <Column field="name" header="Nome da Permissão" sortable>
                    <template #body="{ data }">
                        <code class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-mono">{{ data.name }}</code>
                    </template>
                </Column>

                <Column field="guard_name" header="Guard" style="width: 10rem">
                    <template #body="{ data }">
                        <Tag :value="data.guard_name" severity="secondary" rounded class="text-[10px] px-2 italic uppercase" />
                    </template>
                </Column>

                <Column header="Ações" style="width: 10rem" class="text-right">
                    <template #body="{ data }">
                        <div class="flex gap-2 justify-end">
                            <Button icon="pi pi-pencil" text rounded severity="info" @click="editPermission(data)" />
                            <Button icon="pi pi-trash" text rounded severity="danger" @click="deletePermission(data)" />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <Dialog v-model:visible="displayModal" :header="editMode ? 'Editar Permissão' : 'Nova Permissão'" :style="{ width: '450px' }" modal class="p-fluid">
            <form @submit.prevent="submitForm">
                <div class="field mt-4">
                    <InputLabel for="name" value="Nome da Permissão" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        placeholder="Ex: view_users"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <SecondaryButton @click="closeModal" type="button"> Cancelar </SecondaryButton>
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ editMode ? 'Atualizar' : 'Criar' }}
                    </PrimaryButton>
                </div>
            </form>
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
:deep(.p-dialog-header) {
    border-bottom: 1px solid #f3f4f6;
    padding: 1.5rem;
}
:deep(.p-dialog-content) {
    padding: 1.5rem;
}
</style>

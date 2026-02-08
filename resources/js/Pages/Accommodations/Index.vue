<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { Head, Link, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    accommodations: Object,
});

const form = useForm({});

const deleteAccommodation = (id) => {
    if (confirm('Tem certeza que deseja excluir esta unidade?')) {
        form.delete(route('accommodations.destroy', id));
    }
};

const getStatusLabel = (status) => {
    const labels = {
        'Available': 'Disponível',
        'Occupied': 'Ocupado',
        'Maintenance': 'Manutenção',
        'Cleaning': 'Limpeza',
    };
    return labels[status] || status;
};

const getStatusClass = (status) => {
    const classes = {
        'Available': 'bg-green-100 text-green-800',
        'Occupied': 'bg-blue-100 text-blue-800',
        'Maintenance': 'bg-red-100 text-red-800',
        'Cleaning': 'bg-yellow-100 text-yellow-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Head title="Acomodações" />

    <AppLayout title="Acomodações (Unidades)">


        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-4 flex justify-end">
                            <Link :href="route('accommodations.create')">
                                <PrimaryButton>
                                    Nova Unidade
                                </PrimaryButton>
                            </Link>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Nome/Número
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Tipo
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Propriedade
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Status
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Ações</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="accommodation in accommodations.data" :key="accommodation.id">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ accommodation.name }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ accommodation.type?.name || 'N/A' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ accommodation.type?.property?.name || 'N/A' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                                            :class="getStatusClass(accommodation.status)">
                                            {{ getStatusLabel(accommodation.status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <Link :href="route('accommodations.edit', accommodation.id)" class="mr-4 text-indigo-600 hover:text-indigo-900">
                                            Editar
                                        </Link>
                                        <button @click="deleteAccommodation(accommodation.id)" class="text-red-600 hover:text-red-900">
                                            Excluir
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="mt-4" v-if="accommodations.links.length > 3">
                            <div class="flex flex-wrap -mb-1">
                                <template v-for="(link, key) in accommodations.links" :key="key">
                                    <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded" v-html="link.label" />
                                    <Link v-else class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500" :class="{ 'bg-blue-700 text-white': link.active }" :href="link.url" v-html="link.label" />
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

</template>

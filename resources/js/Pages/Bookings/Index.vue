<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { Head, Link, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    bookings: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');

watch([search, status], ([newSearch, newStatus]) => {
    router.get(route('bookings.index'), { search: newSearch, status: newStatus }, {
        preserveState: true,
        replace: true,
    });
});

const form = useForm({});

const deleteBooking = (id) => {
    if (confirm('Tem certeza que deseja excluir esta reserva?')) {
        form.delete(route('bookings.destroy', id));
    }
};

const formatDate = (dateString) => {
    // Trata diferentes formatos de data que podem vir do backend
    if (!dateString) return '';
    
    // Se for objeto Date, formata diretamente
    if (dateString instanceof Date) {
        return dateString.toLocaleDateString('pt-BR');
    }
    
    // Se for string no formato YYYY-MM-DD ou YYYY-MM-DDTHH:mm:ss
    if (typeof dateString === 'string') {
        // Remove a parte da hora se existir para evitar problemas de fuso
        const dateOnly = dateString.split('T')[0];
        const [year, month, day] = dateOnly.split('-');
        if (year && month && day) {
            return `${day.padStart(2, '0')}/${month.padStart(2, '0')}/${year}`;
        }
    }
    
    // Fallback
    return new Date(dateString).toLocaleDateString('pt-BR');
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
};

const getStatusLabel = (status) => {
    const labels = {
        'pending': 'Pendente',
        'confirmed': 'Confirmada',
        'cancelled': 'Cancelada',
        'completed': 'Concluída',
    };
    return labels[status.toLowerCase()] || status;
};

const getStatusClass = (status) => {
    const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'confirmed': 'bg-green-100 text-green-800',
        'cancelled': 'bg-red-100 text-red-800',
        'completed': 'bg-blue-100 text-blue-800',
    };
    return classes[status.toLowerCase()] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Head title="Reservas" />

    <AppLayout title="Gestão de Reservas">


        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-4 flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="flex flex-1 gap-4 w-full md:w-auto">
                                <TextInput
                                    v-model="search"
                                    type="text"
                                    placeholder="Buscar por cliente..."
                                    class="w-full max-w-sm"
                                />
                                <select
                                    v-model="status"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="">Todos os Status</option>
                                    <option value="pending">Pendente</option>
                                    <option value="confirmed">Confirmada</option>
                                    <option value="cancelled">Cancelada</option>
                                    <option value="completed">Concluída</option>
                                </select>
                            </div>
                            <Link :href="route('bookings.create')">
                                <PrimaryButton>
                                    Nova Reserva
                                </PrimaryButton>
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Cliente
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Acomodação
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Período
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Preço
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
                                    <tr v-for="booking in bookings.data" :key="booking.id">
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ booking.user?.name }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ booking.user?.email }}
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ booking.accommodation?.name }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ booking.accommodation?.type?.name }} - {{ booking.accommodation?.type?.property?.name }}
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ formatDate(booking.check_in) }} - {{ formatDate(booking.check_out) }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-gray-900">
                                            {{ formatCurrency(booking.total_price) }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                                                :class="getStatusClass(booking.status)">
                                                {{ getStatusLabel(booking.status) }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                            <Link :href="route('bookings.edit', booking.id)" class="mr-4 text-indigo-600 hover:text-indigo-900">
                                                Editar
                                            </Link>
                                            <button @click="deleteBooking(booking.id)" class="text-red-600 hover:text-red-900">
                                                Excluir
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4" v-if="bookings.links.length > 3">
                            <div class="flex flex-wrap -mb-1">
                                <template v-for="(link, key) in bookings.links" :key="key">
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

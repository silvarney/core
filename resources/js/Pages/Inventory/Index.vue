<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { Head, useForm, router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    properties: Array,
    allProperties: Array,
    filters: Object,
    calendar: Object,
});

const form = useForm({
    status: '',
});

const selectedPropertyId = ref(props.filters.property_id || '');
const selectedMonth = ref(props.filters.month || new Date().getMonth() + 1);
const selectedYear = ref(props.filters.year || new Date().getFullYear());

const months = [
    { value: 1, label: 'Janeiro' },
    { value: 2, label: 'Fevereiro' },
    { value: 3, label: 'Março' },
    { value: 4, label: 'Abril' },
    { value: 5, label: 'Maio' },
    { value: 6, label: 'Junho' },
    { value: 7, label: 'Julho' },
    { value: 8, label: 'Agosto' },
    { value: 9, label: 'Setembro' },
    { value: 10, label: 'Outubro' },
    { value: 11, label: 'Novembro' },
    { value: 12, label: 'Dezembro' },
];

const currentYear = new Date().getFullYear();
const years = Array.from({ length: 5 }, (_, i) => currentYear - 1 + i);

const filterInventory = () => {
    router.get(route('inventory.index'), { 
        property_id: selectedPropertyId.value,
        month: selectedMonth.value,
        year: selectedYear.value
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
};

watch([selectedPropertyId, selectedMonth, selectedYear], () => {
    filterInventory();
});

// Unit Color Management
const unitColors = {};
const colors = [
    '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', 
    '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1'
];

const getUnitColor = (id) => {
    if (!unitColors[id]) {
        const index = Object.keys(unitColors).length % colors.length;
        unitColors[id] = colors[index];
    }
    return unitColors[id];
};

const normalizeDate = (dateValue) => {
    if (!dateValue) return '';
    
    // Se for objeto Date, formata para YYYY-MM-DD
    if (dateValue instanceof Date) {
        return dateValue.toISOString().split('T')[0];
    }
    
    // Se for string, remove a parte da hora se existir
    if (typeof dateValue === 'string') {
        return dateValue.split('T')[0];
    }
    
    return dateValue;
};

const isOccupied = (accommodation, dateStr) => {
    return accommodation.bookings.some(booking => {
        const checkIn = normalizeDate(booking.check_in);
        const checkOut = normalizeDate(booking.check_out);
        
        const checkInDate = new Date(checkIn);
        const checkOutDate = new Date(checkOut);
        const currentDate = new Date(dateStr);
        
        // Ocupado se a data estiver no período da reserva
        return currentDate >= checkInDate && currentDate <= checkOutDate;
    });
};

const getBookingsForDate = (accommodation, dateStr) => {
    return accommodation.bookings.filter(booking => {
        const checkIn = normalizeDate(booking.check_in);
        const checkOut = normalizeDate(booking.check_out);
        
        const checkInDate = new Date(checkIn);
        const checkOutDate = new Date(checkOut);
        const currentDate = new Date(dateStr);
        
        // Retorna todas as reservas que cobrem esta data
        return currentDate >= checkInDate && currentDate <= checkOutDate;
    });
};

const getBookingForDate = (accommodation, dateStr) => {
    return getBookingsForDate(accommodation, dateStr)[0]; // Mantém compatibilidade com código existente
};

const updateStatus = (accommodationId, newStatus) => {
    form.status = newStatus;
    form.patch(route('inventory.update-status', accommodationId), {
        preserveScroll: true,
    });
};

const getStatusColor = (status) => {
    const colors = {
        'available': 'bg-green-100 text-green-800 border-green-200',
        'occupied': 'bg-blue-100 text-blue-800 border-blue-200',
        'maintenance': 'bg-red-100 text-red-800 border-red-200',
        'cleaning': 'bg-yellow-100 text-yellow-800 border-yellow-200',
    };
    return colors[status] || 'bg-gray-100 text-gray-800 border-gray-200';
};

const getStatusLabel = (status) => {
    const labels = {
        'available': 'Livre',
        'occupied': 'Ocupado',
        'maintenance': 'Manutenção',
        'cleaning': 'Limpeza',
    };
    return labels[status] || status;
};
</script>

<template>
    <Head title="Inventário e Status" />

    <AppLayout title="Inventário de Unidades">


        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Property Selector -->
                <div class="mb-6 bg-white p-4 shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Mapa de Unidades</h2>
                            <p class="text-sm text-gray-500">Selecione uma propriedade para visualizar suas unidades e gerenciar status.</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <!-- Month Selector -->
                            <div class="w-32">
                                <label for="month_filter" class="block text-sm font-medium text-gray-700 mb-1">Mês</label>
                                <select 
                                    id="month_filter"
                                    v-model="selectedMonth"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                >
                                    <option v-for="month in months" :key="month.value" :value="month.value">
                                        {{ month.label }}
                                    </option>
                                </select>
                            </div>

                            <!-- Year Selector -->
                            <div class="w-24">
                                <label for="year_filter" class="block text-sm font-medium text-gray-700 mb-1">Ano</label>
                                <select 
                                    id="year_filter"
                                    v-model="selectedYear"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                >
                                    <option v-for="year in years" :key="year" :value="year">
                                        {{ year }}
                                    </option>
                                </select>
                            </div>

                            <!-- Property Filter -->
                            <div class="w-64">
                                <label for="property_filter" class="block text-sm font-medium text-gray-700 mb-1">Propriedade</label>
                                <select 
                                    id="property_filter"
                                    v-model="selectedPropertyId"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                >
                                    <option value="">Todas as Propriedades</option>
                                    <option v-for="prop in allProperties" :key="prop.id" :value="prop.id">
                                        {{ prop.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Occupancy Calendar (Gantt Style) -->
                <div class="mb-8 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-md font-bold text-gray-700 uppercase tracking-wider">
                            Calendário de Ocupação - {{ calendar.month_name }}
                        </h3>
                        <div class="flex items-center gap-4 text-xs">
                            <div class="flex items-center gap-1"><span class="w-3 h-3 bg-blue-500 rounded-full"></span> Check-in</div>
                            <div class="flex items-center gap-1"><span class="w-3 h-3 bg-purple-500 rounded-full"></span> Ocupação</div>
                            <div class="flex items-center gap-1"><span class="w-3 h-3 bg-red-500 rounded-full"></span> Check-out</div>
                            <div class="flex items-center gap-1"><span class="w-3 h-3 bg-gray-200 rounded-full"></span> Livre</div>
                        </div>
                    </div>
                    <div class="p-0 overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b">
                                    <th class="sticky left-0 bg-gray-50 z-10 px-4 py-2 text-left text-xs font-bold text-gray-500 border-r w-48">Unidade</th>
                                    <th v-for="day in calendar.days" :key="day.date" 
                                        class="px-1 py-2 text-center text-[10px] font-bold border-r min-w-[30px]"
                                        :class="day.is_weekend ? 'bg-yellow-50 text-yellow-700' : 'text-gray-400'"
                                    >
                                        {{ day.day }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="property in properties" :key="'cal-p-' + property.id">
                                    <template v-for="type in property.accommodation_types" :key="'cal-t-' + type.id">
                                        <tr v-for="acc in type.accommodations" :key="'cal-acc-' + acc.id" class="border-b hover:bg-gray-50 group">
                                            <td class="sticky left-0 bg-white group-hover:bg-gray-50 z-10 px-4 py-2 text-xs font-semibold text-gray-700 border-r shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-4 rounded" :style="{ backgroundColor: getUnitColor(acc.id) }"></div>
                                                    {{ acc.name }}
                                                </div>
                                            </td>
                                            <td v-for="day in calendar.days" :key="'cal-acc-' + acc.id + day.date" 
                                                class="p-0 border-r h-8"
                                                :class="day.is_weekend ? 'bg-yellow-50/30' : ''"
                                            >
                                                 <div v-if="isOccupied(acc, day.date)" 
                                                     class="w-full h-full flex items-center justify-center p-1 gap-px"
                                                 >
                                                    <template v-for="booking in getBookingsForDate(acc, day.date)" :key="booking.id">
                                                        <div 
                                                            class="flex-1 h-3 rounded-sm shadow-sm" 
                                                            :class="{
                                                                'bg-blue-500/80': normalizeDate(booking.check_in) === day.date, // Check-in hoje
                                                                'bg-red-500/80': normalizeDate(booking.check_out) === day.date, // Check-out hoje
                                                                'bg-purple-500/80': normalizeDate(booking.check_in) !== day.date && normalizeDate(booking.check_out) !== day.date // No meio
                                                            }"
                                                            :title="`${booking.user?.name || 'Hóspede'}\n${normalizeDate(booking.check_in) === day.date ? 'Check-in' : normalizeDate(booking.check_out) === day.date ? 'Check-out' : 'Ocupação contínua'}`"
                                                        ></div>
                                                    </template>
                                                 </div>
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Unit Status Cards -->
                <div v-for="property in properties" :key="property.id" class="mb-8 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-700">{{ property.name }}</h3>
                        <p class="text-sm text-gray-500">{{ property.location }}</p>
                    </div>

                    <div class="p-6">
                        <div v-for="type in property.accommodation_types" :key="type.id" class="mb-6 last:mb-0">
                            <h4 class="mb-3 text-md font-semibold text-gray-600 border-l-4 border-indigo-500 pl-2">
                                {{ type.name }}
                            </h4>

                            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                                <div v-for="acc in type.accommodations" :key="acc.id" 
                                    class="relative flex flex-col items-center justify-center rounded-lg border p-4 transition-all hover:shadow-md border-t-8"
                                    :class="getStatusColor(acc.status)"
                                    :style="{ borderTopColor: getUnitColor(acc.id) }"
                                >
                                    <span class="text-lg font-bold">{{ acc.name }}</span>
                                    <span class="mt-1 text-xs font-medium uppercase tracking-wider">
                                        {{ getStatusLabel(acc.status) }}
                                    </span>

                                    <!-- Quick Actions Menu -->
                                    <div class="mt-3 flex gap-1">
                                        <button 
                                            v-if="acc.status !== 'available'"
                                            @click="updateStatus(acc.id, 'available')"
                                            class="rounded bg-white/50 p-1 text-[10px] hover:bg-white"
                                            title="Liberar"
                                        >
                                            ✅
                                        </button>
                                        <button 
                                            v-if="acc.status !== 'cleaning'"
                                            @click="updateStatus(acc.id, 'cleaning')"
                                            class="rounded bg-white/50 p-1 text-[10px] hover:bg-white"
                                            title="Limpeza"
                                        >
                                            🧹
                                        </button>
                                        <button 
                                            v-if="acc.status !== 'maintenance'"
                                            @click="updateStatus(acc.id, 'maintenance')"
                                            class="rounded bg-white/50 p-1 text-[10px] hover:bg-white"
                                            title="Manutenção"
                                        >
                                            🛠️
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="property.accommodation_types.length === 0" class="py-4 text-center text-gray-500">
                            Nenhum tipo de acomodação cadastrado para esta propriedade.
                        </div>
                    </div>
                </div>

                <div v-if="properties.length === 0" class="py-12 text-center text-gray-500 bg-white rounded-lg shadow">
                    Nenhuma propriedade cadastrada.
                </div>
            </div>
        </div>
    </AppLayout>

</template>

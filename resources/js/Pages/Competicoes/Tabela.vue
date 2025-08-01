
<script setup lang="ts">
import Chaveamento from '@/Pages/Competicoes/Chaveamento.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import NavLink from '@/Components/NavLink.vue';

const props = defineProps<{
    competidores1: CompetidoresTabela[],
    competidores2: CompetidoresTabela[],
    competicao: Competicao
}>();

</script>

<template>
    <Head title="Tabela Competição" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Tabela competição 
            </h2>
        </template>
        <Chaveamento :competidores1="competidores1" :competidores2="competidores2" :competicao="competicao" />
    </AuthenticatedLayout>
</template>

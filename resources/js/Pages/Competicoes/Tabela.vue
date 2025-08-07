<script setup lang="ts">
import Chaveamento from '@/Pages/Competicoes/Chaveamento.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import NavLink from '@/Components/NavLink.vue';
import FormButton from '@/Components/FormButton.vue';

const props = defineProps<{
    competidores1: CompetidoresTabela[],
    competidores2: CompetidoresTabela[],
    competicao: Competicao
}>();

function imprimirPagina() {
    window.print();
}

</script>

<template>
    <Head title="Tabela Competição" class="no-print"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200 no-print">
                Tabela competição
            </h2>
        </template>
        <div class="flex flex-col bg-gray-900 no-print">
            <div class="flex justify-end p-4">
                <NavLink :href="route('competicao.gerar-tabela-competicao', [competicao])"
                    class="mr-1 items-center rounded-md border border-transparent bg-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-300 focus:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-stone-950 dark:bg-gray-300 dark:text-stone-950 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-gray-300 dark:active:bg-gray-300"
                    :button="true">
                    Voltar
                </NavLink>
                <FormButton type="button" @click="imprimirPagina()"
                    class="items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Imprimir
                </FormButton>
            </div>
        </div>
        <Chaveamento class="print-area" :competidores1="props.competidores1" :competidores2="props.competidores2"
            :competicao="props.competicao" />
    </AuthenticatedLayout>
</template>

<style>
    @media print {
        .overflow-x-auto {
            overflow-x: visible !important;
        }

        .w-screen {
            width: 100% !important;
        }

        /* Oculta todo o conteúdo */
        html, body {
            width: 100%;
            overflow: visible !important;
            zoom: 0.80; /* Ajuste a escala se necessário */
        }

        body * {
            visibility: hidden !important;
        }

        /* Torna visível apenas o que está na .print-area */
        .print-area, .print-area * {
            visibility: visible !important;
        }

        /* Garante que a área apareça corretamente na impressão */
        .print-area {
            width: 100% !important;
            overflow: visible !important;
        }

        @page {
            size: landscape;
            margin: 1cm;
        }

        /* Oculta áreas de navegação, botões, etc */
        .no-print {
            display: none !important;
        }

        * {
            box-sizing: border-box;
        }

        body {
            color: #000 !important;
            background: #fff !important;
        }

        .print-area * {
            color: #000 !important;
            font-weight: bold !important;
            opacity: 1 !important;
        }

        .text-gray-300, .text-gray-400, .opacity-50 {
            color: #000 !important;
            opacity: 1 !important;
        }

        .border-white {
            border: 1px solid !important;
            border-color: #000 !important;
        }

        .text-white {
            color: #000 !important;
        }

        .bg-gray-900 {
            background-color: #fff !important;
        }

        .hover\:bg-gray-700:hover {
            background-color: transparent !important;
        }

        .opacity-50 {
            opacity: 1 !important;
        }
    }
</style>

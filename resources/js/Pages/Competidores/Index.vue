
<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head } from '@inertiajs/vue3';
import { IPagination } from '@/types/ipagination';
import NavLink from '@/Components/NavLink.vue';

const props = defineProps<{
    competidores: IPagination
}>();

</script>

<template>
    <Head title="Competidores" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Competidores 
            </h2>

            
        </template>

        <div class="bg-white shadow dark:bg-gray-800 mt-3 mr-auto ml-auto w-4/5 mx-auto">
            <NavLink
                class="float-right mt-2 mr-2 inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 dark:bg-gray-200 dark:text-gray-800 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-gray-800 dark:active:bg-gray-300"
                :href="route('competidores.create')"
            >
                Novo
            </NavLink>

            <table class="table-auto w-full text-white">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Faixa</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(competidor, index) in props.competidores.data">
                        <tr>
                            <td>{{competidor.id}}</td>
                            <td>{{competidor.nome}}</td>
                            <td>{{competidor.faixa}}</td>
                            <td></td>
                        </tr>
                    </template>
                </tbody>
            </table>
    
            <Pagination :pagination="props.competidores" />
        </div>

    </AuthenticatedLayout>
</template>

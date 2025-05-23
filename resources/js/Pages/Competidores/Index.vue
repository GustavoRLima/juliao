
<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head } from '@inertiajs/vue3';
import { IPagination } from '@/types/ipagination';
import PrimaryButton from '@/Components/PrimaryButton.vue';

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
            <PrimaryButton
                class="float-right mt-2 mr-2"
            >
                Novo
            </PrimaryButton>

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

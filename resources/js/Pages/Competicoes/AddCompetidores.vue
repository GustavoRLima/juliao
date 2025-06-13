
<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { submitForm, submitFormPut } from '@/config/usaForm';
import FormSelect from '@/Components/FormSelect.vue';
import AutoComplete from '@/Components/AutoComplete.vue';

const props = defineProps<{
    competidores: Competicao
}>();

interface CompeticaoCompetidor extends Record<string, any> {
    competidores: {
        competidor_id: number;
        categoria: string;
    }[];
}

const form = useForm<CompeticaoCompetidor>({
    competidores: []
})

async function formSubmit()
{
    
}

</script>

<template>
    <Head title="Competicao" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Adicionando competidores
            </h2>
        </template>

        <div class="bg-white shadow dark:bg-gray-800 mt-3 mr-auto ml-auto w-4/5 mx-auto p-5">
            <form @submit.prevent="formSubmit">
                <template v-if="form.competidores.length > 0">
                </template>
            </form>
        </div>

    </AuthenticatedLayout>
</template>


<script setup lang="ts">
import FormInput from '@/Components/FormInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { submitForm, submitFormPut } from '@/config/usaForm';
import FormButton from '@/Components/FormButton.vue';
import FormSelect from '@/Components/FormSelect.vue';
import NavLink from '@/Components/NavLink.vue';

const props = defineProps<{
    competicao: Competicao
}>();

const form = useForm({
    descricao: props.competicao?.descricao ?? '',
    data_evento: props.competicao?.data_evento ?? '',
})

async function formSubmit()
{
    if(props.competicao){
        await submitFormPut(form, route('competicoes.update', [props.competicao.id]));
    }else{
        await submitForm(form, route('competicoes.store'));
    }
}

</script>

<template>
    <Head title="Competicao" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Nova Competição
            </h2>
        </template>

        <div class="bg-white shadow dark:bg-gray-800 mt-3 mr-auto ml-auto w-4/5 mx-auto p-5">
            <form @submit.prevent="formSubmit">
                <FormInput
                    type="text"
                    label="Descrição"
                    id="descricao"
                    name="descricao"
                    v-model="form.descricao"
                    :required="true"
                >
                </FormInput>
                
                <FormInput
                    type="date"
                    label="Data do evento"
                    id="data_evento"
                    name="data_evento"
                    v-model="form.data_evento"
                    :required="true"
                >
                </FormInput>
                

                <div class="flex justify-end">
                    <div class="ml-auto mt-2 inline-flex">
                        <NavLink
                            :href="route('competicoes.index')"
                            class="mr-1 items-center rounded-md border border-transparent bg-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-300 focus:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-stone-950 dark:bg-gray-300 dark:text-stone-950 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-gray-300 dark:active:bg-gray-300"
                            :button="true"
                        >
                            Voltar
                        </NavLink>
                        <FormButton
                            type="submit"
                            class="mr-1 items-center rounded-md border border-transparent bg-green-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-green-300 focus:bg-green-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-stone-950 dark:bg-green-300 dark:text-stone-950 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-green-300 dark:active:bg-green-300"
                            :disabled="form.processing"
                        >
                            Salvar
                        </FormButton>
                    </div>
                </div>
            </form>
        </div>

    </AuthenticatedLayout>
</template>

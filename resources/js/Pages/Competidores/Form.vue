
<script setup lang="ts">
import FormInput from '@/Components/FormInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { watch } from 'vue';
import { submitForm, submitFormPut } from '@/config/usaForm';
import FormButton from '@/Components/FormButton.vue';

const props = defineProps<{
    competidor: Competidores
}>();

const form = useForm({
    nome: props.competidor?.nome ?? '',
    data_nascimento: props.competidor?.data_nascimento ?? '',
    idade: props.competidor?.idade ?? '',
    peso: props.competidor?.peso ?? '',
    faixa: props.competidor?.faixa ?? '',
    categoria: props.competidor?.categoria ?? '',
})

watch(() => form.data_nascimento, (newValue) => {
    if (!newValue) return form.idade = 0;

    const today = new Date()
    const birth = new Date(newValue)

    let years = today.getFullYear() - birth.getFullYear()
    const monthDiff = today.getMonth() - birth.getMonth()
    const dayDiff = today.getDate() - birth.getDate()

    if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
        years--
    }

    form.idade = years;
})

async function formSubmit()
{
    if(props.competidor){
        await submitFormPut(form, route('competidores.update', [props.competidor.id]));
    }else{
        await submitForm(form, route('competidores.store'));
    }
}

</script>

<template>
    <Head title="Competidores" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Novo Competidor
            </h2>
        </template>

        <div class="bg-white shadow dark:bg-gray-800 mt-3 mr-auto ml-auto w-4/5 mx-auto p-5">
            <form @submit.prevent="formSubmit">
                <FormInput
                    type="text"
                    label="Nome"
                    id="nome"
                    name="nome"
                    v-model="form.nome"
                    :required="true"
                >
                </FormInput>
                
                <FormInput
                    type="date"
                    label="Data de nascimento"
                    id="data_nascimento"
                    name="data_nascimento"
                    v-model="form.data_nascimento"
                >
                </FormInput>
                
                <FormInput
                    type="text"
                    label="Idade"
                    id="idade"
                    name="idade"
                    v-model="form.idade"
                    readonly
                >
                </FormInput>

                <div class="flex justify-end">
                    <FormButton
                        type="submit"
                        class="ml-auto mt-2 inline-flex items-center rounded-md border border-transparent bg-green-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-green-300 focus:bg-green-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-stone-950 dark:bg-green-300 dark:text-stone-950 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-green-300 dark:active:bg-green-300"
                        :disabled="form.processing"
                    >
                        Salvar
                    </FormButton>
                </div>
            </form>
        </div>

    </AuthenticatedLayout>
</template>

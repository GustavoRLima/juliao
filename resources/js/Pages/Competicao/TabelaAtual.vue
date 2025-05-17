<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'
import Draggable from 'vuedraggable'

interface Competitor {
    id: number
    name: string
    positionInit: number[]
    looser: boolean
}

type Match = Competitor[]
type Round = Match[]

const competitors = ref<Competitor[]>([
    { id: 1, name: 'João', positionInit: [0], looser: false },
    { id: 2, name: 'Maria', positionInit: [0], looser: false },
    { id: 3, name: 'Carlos', positionInit: [1], looser: false },
    { id: 4, name: 'Ana', positionInit: [1], looser: false },
    { id: 5, name: 'José', positionInit: [2], looser: false },
    { id: 6, name: 'Fernanda', positionInit: [2], looser: false },
    { id: 7, name: 'Lucas', positionInit: [3], looser: false },
    { id: 8, name: 'Beatriz', positionInit: [3], looser: false },
    // Teste com ímpar:
    { id: 9, name: 'Daniela', positionInit: [4], looser: false },
])

const rounds = ref<Round[]>([])

function generateInitialRounds(): void {
    const firstRound: Match[] = []
    const shuffled = [...competitors.value] // (Opcional) embaralhar

    for (let i = 0; i < shuffled.length; i += 2) {
        const match: Match = [shuffled[i]]
        if (shuffled[i + 1]) match.push(shuffled[i + 1]) // suporte a competidor solo
        firstRound.push(match)
    }

    rounds.value = [firstRound];

    mountAllRounds();
}

function mountAllRounds(): void {
    let all = competitors.value.length / 2;
    if (!Number.isInteger(all)) all = all + 0.5;
    for (let roundIndex = 1; roundIndex < all; roundIndex++) {
        rounds.value[roundIndex] = [];
        let grupo = all / (roundIndex + 1);
        grupo = arredondarPersonalizado(grupo);
        if (grupo == 0) grupo = 1;
        for (let matchIndex = 0; matchIndex < grupo; matchIndex++) {
            rounds.value[roundIndex][matchIndex] = [];
        }
    }
}

function arredondarPersonalizado(valor: number): number {
    const parteInteira = Math.floor(valor)
    const parteDecimal = valor - parteInteira
    return parteDecimal < 0.5 ? Math.floor(valor) : Math.ceil(valor)
}

async function handleAdvance(
    winner: Competitor,
    roundIndex: number,
    matchIndex: number
) {
    // Só permite avançar da rodada atual (não das futuras)
    if(winner.looser) {
        Swal.fire("Alerta", "Esse competidor não pode avançar pois perdeu!", "warning");
        return;
    }

    const nextRoundIndex = roundIndex + 1
    const targetMatchIndex = Math.floor(matchIndex / 2)

    if (!rounds.value[nextRoundIndex]) {
        return;
    }

    const result = await Swal.fire({
        title: `Avançar ${winner.name}?`,
        text: 'Deseja confirmar que esse competidor venceu a partida?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não',
    })

    if (!result.isConfirmed) return

    const lastMatch = rounds.value[roundIndex]

    const nextMatch = rounds.value[nextRoundIndex][targetMatchIndex]
    const alreadyExists = nextMatch.some((c) => c.id === winner.id)

    if (!alreadyExists) {
        nextMatch.push(winner)
        winner.positionInit.push(targetMatchIndex)
        await refreshMatch(winner, lastMatch);
        console.log(lastMatch[matchIndex][0]);
        lastMatch[matchIndex][0].looser = true
    }
    
}

async function handleRevert(competitor: Competitor, roundIndex: number) {    
    const currentRound = rounds.value[roundIndex]
    const previousRound = rounds.value[roundIndex - 1]

    if (!previousRound) return

    const result = await Swal.fire({
        title: `Avançar ${competitor.name}?`,
        text: 'Deseja voltar esse competidor?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não',
    })

    if (!result.isConfirmed) return

    if (competitor && competitor.positionInit.length > 1) {
        competitor.positionInit.pop()
    }

    const matchIndex = competitor.positionInit[competitor.positionInit.length - 1];

    const returnMatch = previousRound[matchIndex];
    returnMatch.push(competitor)
    returnMatch.map((c) => c.looser = false);

    // Remove da rodada atual
    refreshMatch(competitor, currentRound);

    // Opcional: você pode inserir de volta no match anterior original
}

function refreshMatch(competitor: Competitor, currentRound: Round) {
    for (const match of currentRound) {

        const index = match.findIndex((c) => c.id === competitor.id)
        if (index !== -1) {
            match.splice(index, 1)
        }
    }
}


onMounted(() => {
    generateInitialRounds()
})
</script>

<template>

    <Head title="Tabela Competição" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Tabela competição 23
            </h2>
        </template>

        <!-- <div class="flex space-x-6 overflow-x-auto p-4">
            <div v-for="(round, roundIndex) in rounds" :key="roundIndex" class="flex flex-col gap-4">
                <div class="text-center text-white font-bold text-lg">Rodada {{ roundIndex + 1 }}</div>

                <Draggable v-model="rounds[roundIndex]" group="matches" item-key="index" class="flex flex-col gap-4"
                    :sort="true">
                    <template #item="{ elementDad: match, index: matchIndex }">
                        <div class="w-full">
                            <div class="bg-gray-100 rounded-xl p-4 min-w-[200px] shadow-md">
                                <Draggable v-model="rounds[roundIndex][matchIndex]" group="competitors" item-key="id"
                                    class="space-y-2" :sort="true">
                                    <template #item="{ element: competitor }">
                                        <div class="w-full">
                                            <div class="flex items-center justify-between gap-2 bg-white p-2 rounded shadow border text-center cursor-pointer hover:bg-green-100 transition"
                                                @click="handleAdvance(competitor, roundIndex, matchIndex)">
                                                <span class="flex-1 text-sm">{{ competitor.name }}</span>
                                                <button v-if="roundIndex > 0"
                                                    @click.stop="handleRevert(competitor, roundIndex)"
                                                    class="text-red-500 text-xs hover:underline"
                                                    title="Voltar para rodada anterior">
                                                    X
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </Draggable>
                            </div>
                        </div>
                    </template>
                </Draggable>
            </div>
        </div> -->

        <div class="flex space-x-6 overflow-x-auto p-4">
            <div v-for="(round, roundIndex) in rounds" :key="roundIndex" class="flex flex-col gap-4">
                <div class="text-center text-white font-bold text-lg"> 
                    <span v-if="(roundIndex + 1) == rounds.length">Vencedor</span>
                    <span v-else>Rodada {{ roundIndex + 1 }}</span>
                </div>

                <div v-for="(match, matchIndex) in round" :key="matchIndex"
                    class="bg-gray-100 rounded-xl p-4 min-w-[180px] shadow-md">
                    <div v-for="competitor in match" :key="competitor.id"
                        class="flex items-center justify-between gap-2 bg-white p-2 rounded shadow border text-center cursor-pointer hover:bg-green-100 transition"
                        @click="handleAdvance(competitor, roundIndex, matchIndex)">
                        <span class="flex-1 text-sm">{{ competitor.name }}</span>
                        <button v-if="roundIndex > 0 && !competitor.looser"
                            @click.stop="handleRevert(competitor, roundIndex)"
                            class="text-red-500 text-xs hover:underline"
                            title="Voltar para rodada anterior">
                            X
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<style scoped>
.bracket {
  display: flex;
  gap: 20px;
}
.round {
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.match {
  background: #eee;
  padding: 10px;
  border-radius: 8px;
}
.competitor {
  background: #fff;
  padding: 5px;
  margin-bottom: 5px;
  border: 1px solid #ccc;
}
</style>
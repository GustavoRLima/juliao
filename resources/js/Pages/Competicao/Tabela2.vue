<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import Draggable from 'vuedraggable';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

interface Competitor {
    id: number
    name: string
}

type Match = Competitor[]
type Round = Match[]

const competitors = ref<Competitor[]>([
    { id: 1, name: 'João' },
    { id: 2, name: 'Maria' },
    { id: 3, name: 'Carlos' },
    { id: 4, name: 'Ana' },
    { id: 5, name: 'José' },
    { id: 6, name: 'Fernanda' },
    { id: 7, name: 'Lucas' },
    { id: 8, name: 'Beatriz' },
])

const rounds = ref<Round[]>([])

function generateInitialRounds(): void {
    const firstRound: Match[] = []

    for (let i = 0; i < competitors.value.length; i += 2) {
        firstRound.push([competitors.value[i], competitors.value[i + 1]])
    }

    rounds.value = [firstRound]
}

function onAdd(event: any, nextRoundIndex: number, matchIndex: number): void {
    const player: Competitor = event.item?._underlying_vm_ || event.clone

    if (!player || !player.name) return

    const confirmed = window.confirm(
        `Deseja avançar ${player.name} para a próxima etapa?`
    )

    if (!confirmed) {
        // Corrigido: Acessar o match correto da rodada atual
        const currentRoundIndex = nextRoundIndex - 1
        const currentMatch = rounds.value[currentRoundIndex]?.[matchIndex]

        if (currentMatch) {
            const idx = currentMatch.findIndex((c) => c.id === player.id)
            if (idx !== -1) currentMatch.splice(idx, 1)
        }

        return
    }

    // Cria a próxima rodada se ainda não existe
    if (!rounds.value[nextRoundIndex]) {
        rounds.value[nextRoundIndex] = []
    }

    const targetMatchIndex = Math.floor(matchIndex / 2)

    if (!rounds.value[nextRoundIndex][targetMatchIndex]) {
        rounds.value[nextRoundIndex][targetMatchIndex] = []
    }

    const targetMatch = rounds.value[nextRoundIndex][targetMatchIndex]

    const alreadyExists = targetMatch.some((c) => c.id === player.id)
    if (!alreadyExists) {
        targetMatch.push(player)
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

        <div class="flex space-x-6 overflow-x-auto p-4">
            <div v-for="(round, roundIndex) in rounds" :key="roundIndex" class="flex flex-col gap-4">
                <div class="text-center text-white font-bold text-lg">Rodada {{ roundIndex + 1 }}</div>

                <div v-for="(match, matchIndex) in round" :key="matchIndex"
                    class="bg-gray-100 rounded-xl p-4 min-w-[180px] shadow-md">
                    <Draggable :list="match" group="competitors" item-key="id" class="space-y-2"
                        @add="onAdd($event, roundIndex + 1, matchIndex)">
                        <template #item="{ element }">
                            <div
                                class="bg-white p-2 rounded shadow border text-center cursor-pointer hover:bg-blue-100 transition">
                                {{ element.name }}
                            </div>
                        </template>
                    </Draggable>
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

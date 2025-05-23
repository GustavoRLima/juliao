<template>
  <div class="flex flex-col items-center bg-gray-900 min-h-screen p-6">
    <h1 class="text-2xl font-bold mb-6 text-white">Chave de Competição</h1>

    <div class="flex overflow-x-auto">
      <!-- Lado Esquerdo -->
      <div class="flex">
        <div
          v-for="(round, rIndex) in leftRounds"
          :key="'left-' + rIndex"
          class="flex flex-col items-center mx-2"
          :style="{ marginTop: `${(2 ** rIndex - 1) * 16}px` }"
        >
          <div
            v-for="(match, mIndex) in getMatches(round)"
            :key="'left-match-' + mIndex"
            class="flex flex-col justify-center items-center border border-white rounded-lg mb-6 w-48"
          >
            <div
              v-for="(player, pIndex) in match"
              :key="pIndex"
              class="w-full h-12 flex items-center justify-center cursor-pointer text-white hover:bg-gray-700 text-sm"
              :class="{
                'bg-green-700': isWinner('left', rIndex, mIndex, pIndex),
                'opacity-50 pointer-events-none': opponentWon('left', rIndex, mIndex, pIndex),
              }"
              @click="handleClick('left', rIndex, mIndex, pIndex)"
            >
              {{ player || '--' }}
            </div>
          </div>
        </div>
      </div>

      <!-- Final -->
      <div class="flex flex-col justify-center mx-4">
        <div class="w-32 h-16 border border-yellow-500 rounded-lg p-2 mb-4 flex items-center justify-center text-center font-semibold text-yellow-500">
          FINAL
        </div>
      </div>

      <!-- Lado Direito -->
      <div class="flex">
        <div
          v-for="(round, rIndex) in rightRounds"
          :key="'right-' + rIndex"
          class="flex flex-col items-center mx-2"
          :style="{ marginTop: `${(2 ** rIndex - 1) * 16}px` }"
        >
          <div
            v-for="(match, mIndex) in getMatches(round)"
            :key="'right-match-' + mIndex"
            class="flex flex-col justify-center items-center border border-white rounded-lg mb-6 w-48"
          >
            <div
              v-for="(player, pIndex) in match"
              :key="pIndex"
              class="w-full h-12 flex items-center justify-center cursor-pointer text-white hover:bg-gray-700 text-sm"
              :class="{
                'bg-green-700': isWinner('right', rIndex, mIndex, pIndex),
                'opacity-50 pointer-events-none': opponentWon('right', rIndex, mIndex, pIndex),
              }"
              @click="handleClick('right', rIndex, mIndex, pIndex)"
            >
              {{ player || '--' }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, computed } from 'vue'

interface Props {
  competitors: string[]
}

const props = defineProps<Props>()

function getNextPowerOfTwo(n: number): number {
  return Math.pow(2, Math.ceil(Math.log2(n)))
}

function generateRounds(players: (string | null)[]) {
  const rounds: (string | null)[][] = []
  let current = players
  while (current.length > 1) {
    rounds.push([...current])
    current = Array(Math.ceil(current.length / 2)).fill(null)
  }
  rounds.push([null]) // final
  return rounds
}

const leftState = reactive({ rounds: [] as (string | null)[][] })
const rightState = reactive({ rounds: [] as (string | null)[][] })

function initRounds() {
  const half = Math.ceil(props.competitors.length / 2)
  const left = [...props.competitors.slice(0, half)]
  const right = [...props.competitors.slice(half)]

  const leftFilled = [...left, ...Array(getNextPowerOfTwo(half) - left.length).fill(null)]
  const rightFilled = [...right, ...Array(getNextPowerOfTwo(right.length) - right.length).fill(null)]

  leftState.rounds = generateRounds(leftFilled)
  rightState.rounds = generateRounds(rightFilled)
}

// Agrupa jogadores em duplas (matches)
function getMatches(round: (string | null)[]) {
  const matches = []
  for (let i = 0; i < round.length; i += 2) {
    matches.push([round[i], round[i + 1]])
  }
  return matches
}

// Verifica se esse jogador já venceu
function isWinner(side: 'left' | 'right', roundIndex: number, matchIndex: number, playerIndex: number) {
  const currentSide = side === 'left' ? leftState : rightState
  const nextRound = roundIndex + 1
  const winner = currentSide.rounds[nextRound]?.[matchIndex]
  return winner && winner === currentSide.rounds[roundIndex][matchIndex * 2 + playerIndex]
}

// Verifica se o oponente já venceu
function opponentWon(side: 'left' | 'right', roundIndex: number, matchIndex: number, playerIndex: number) {
  const currentSide = side === 'left' ? leftState : rightState
  const nextRound = roundIndex + 1
  const thisPlayer = currentSide.rounds[roundIndex][matchIndex * 2 + playerIndex]
  const otherIndex = playerIndex === 0 ? 1 : 0
  const opponent = currentSide.rounds[roundIndex][matchIndex * 2 + otherIndex]
  const winner = currentSide.rounds[nextRound]?.[matchIndex]
  return winner && winner === opponent
}

// Lógica de clique com confirmação e reversão
function handleClick(side: 'left' | 'right', roundIndex: number, matchIndex: number, playerIndex: number) {
  const currentSide = side === 'left' ? leftState : rightState
  const currentPlayer = currentSide.rounds[roundIndex][matchIndex * 2 + playerIndex]
  const nextRound = roundIndex + 1

  if (!currentPlayer) return

  const winner = currentSide.rounds[nextRound]?.[matchIndex]
  if (winner === currentPlayer) {
    if (confirm(`Deseja remover a vitória de ${currentPlayer}?`)) {
      currentSide.rounds[nextRound][matchIndex] = null
    }
  } else if (!winner) {
    if (confirm(`Deseja declarar ${currentPlayer} como vencedor?`)) {
      currentSide.rounds[nextRound][matchIndex] = currentPlayer
    }
  }
}

initRounds()

const leftRounds = computed(() => leftState.rounds.slice(0, -1))
const rightRounds = computed(() => rightState.rounds.slice(0, -1).reverse())
</script>

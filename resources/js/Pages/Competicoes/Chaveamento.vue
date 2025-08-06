<script setup lang="ts">
import { reactive, computed, ref, onMounted } from 'vue'
import Swal from 'sweetalert2'
import Competidores from './Competidores.vue';
import NavLink from '@/Components/NavLink.vue';
import { axiosGet } from '@/config/axiosConfig';

interface Props {
  competicao: Competicao,
  competidores1: CompetidoresTabela[]
  competidores2: CompetidoresTabela[]
}

const props = defineProps<Props>()

function getNextPowerOfTwo(n: number): number {
  return Math.pow(2, Math.ceil(Math.log2(n)))
}

const roundIndex = ref<number>(0)

function generateRounds(players: CompetidoresTabela[]): (Array<CompetidoresTabela | null>)[] {
  const rounds: (Array<CompetidoresTabela | null>)[] = []
  let current = [...players]
  roundIndex.value = 0

  while (current.length > 1) {
    // Cria o array do round atual
    const roundAtual: (CompetidoresTabela | null)[] = new Array(current.length).fill(null)

    // Preenche o round atual com os competidores
    current.forEach((player, index) => {
      if (player && player.vitorias >= roundIndex.value) {
        roundAtual[index] = player
      }
    })

    rounds.push(roundAtual)

    // Calcula o próximo round
    const proximoTamanho = Math.ceil(current.length / 2)
    const proximoRound = new Array(proximoTamanho).fill(null)

    // Apenas competidores com vitorias > roundIndex avançam
    roundAtual.forEach((player, index) => {
      if (player && player && player.vitorias > roundIndex.value) {
        const nextIndex = Math.floor(index / 2)
        proximoRound[nextIndex] = player
      }
    })

    current = proximoRound
    roundIndex.value++
  }

  // Adiciona a final (último round) — pode ter apenas 1 ou nenhum competidor
  rounds.push(current)

  return rounds
}

const leftState = reactive({ rounds: [] as (CompetidoresTabela | null)[][] })
const rightState = reactive({ rounds: [] as (CompetidoresTabela | null)[][] })
const finalMatch = ref<(CompetidoresTabela | null)[]>([])
const finalWinner = ref<CompetidoresTabela | null>(null)
const secondPlace = ref<CompetidoresTabela | null>(null)
const thirdPlaces = ref<CompetidoresTabela[]>([])
const showPodium = ref(false)

async function initRounds() {
  leftState.rounds = generateRounds(props.competidores1)
  rightState.rounds = generateRounds(props.competidores2)
  finalMatch.value = [null, null]

  finalWinner.value = null
  secondPlace.value = null
  thirdPlaces.value = []
  showPodium.value = false

  haveWiner(props.competidores1, 'left')
  haveWiner(props.competidores2, 'right')


  haveFinalWinner();
}

function haveWiner(players: (CompetidoresTabela | null)[], side: 'left' | 'right') {
  let current = [...players]
  const currentWinner = current.filter(p => p && p.vitorias >= roundIndex.value);
  if(currentWinner.length > 0){
    finalMatch.value[side === 'left' ? 0 : 1] = currentWinner[0]
  }
}

function haveFinalWinner()
{
  const currentFinalWinner1 = props.competidores1.filter(p => p && p.vitorias > roundIndex.value);
  if(currentFinalWinner1.length > 0){
    finaWin(currentFinalWinner1[0], false);
    return
  }

  const currentFinalWinner2 = props.competidores2.filter(p => p && p.vitorias > roundIndex.value);
  if(currentFinalWinner2.length > 0){
    finaWin(currentFinalWinner2[0], false);
    return
  }
}



function getMatches(round: (CompetidoresTabela | null)[]) {
  const matches = []
  for (let i = 0; i < round.length; i += 2) {
    matches.push([round[i], round[i + 1]])
  }
  return matches
}

function isWinner(side: 'left' | 'right', roundIndex: number, matchIndex: number, playerIndex: number) {
  const currentSide = side === 'left' ? leftState : rightState
  const nextRound = roundIndex + 1
  const winner = currentSide.rounds[nextRound]?.[matchIndex]
  return winner && winner === currentSide.rounds[roundIndex][matchIndex * 2 + playerIndex]
}

function opponentWin(side: 'left' | 'right', roundIndex: number, matchIndex: number, playerIndex: number) {
  const currentSide = side === 'left' ? leftState : rightState
  const nextRound = roundIndex + 1
  const thisPlayer = currentSide.rounds[roundIndex][matchIndex * 2 + playerIndex]
  const otherIndex = playerIndex === 0 ? 1 : 0
  const opponent = currentSide.rounds[roundIndex][matchIndex * 2 + otherIndex]
  const winner = currentSide.rounds[nextRound]?.[matchIndex]
  return winner && winner === opponent
}


async function handleClick(side: 'left' | 'right', roundIndex: number, matchIndex: number, playerIndex: number, add_winner: boolean = false) {
  const currentSide = side === 'left' ? leftState : rightState
  const currentPlayer = currentSide.rounds[roundIndex][matchIndex * 2 + playerIndex]
  const nextRound = roundIndex + 1

  if(roundIndex > 0){
    const numberValid = parOuImpar(playerIndex);
    if(!currentSide.rounds[roundIndex][matchIndex * 2 + (playerIndex+numberValid)]){
      Swal.fire('Alerta', 'Informe o adversario para continuar!', 'warning');
      return;
    }
  }

  if (!currentPlayer) return

  const winner = currentSide.rounds[nextRound]?.[matchIndex]
  if (winner === currentPlayer) {
    if(Number(currentPlayer.vitorias) != (roundIndex + 1)){
      Swal.fire('Alerta', 'Não é possivel cancelar esta vitoria!', 'warning');
      return;
    }
    const confirmed = await Swal.fire({
      title: `Deseja remover a vitória de ${currentPlayer.nome}?`,
      showCancelButton: true,
      confirmButtonText: 'Sim',
      cancelButtonText: 'Não'
    })
    if (confirmed.isConfirmed) {

      currentSide.rounds[nextRound][matchIndex] = null

      const otherIndex = playerIndex === 0 ? 1 : 0
      const opponent = currentSide.rounds[roundIndex][matchIndex * 2 + otherIndex]

      declareWin(currentPlayer, opponent, 0, 0)
    }
  } else if (!winner) {
    const confirmed = await Swal.fire({
      title: `Deseja declarar ${currentPlayer.nome} como vencedor?`,
      showCancelButton: true,
      confirmButtonText: 'Sim',
      cancelButtonText: 'Não'
    })
    if (confirmed.isConfirmed) {
      currentSide.rounds[nextRound][matchIndex] = currentPlayer
      if(!add_winner){
  
        const otherIndex = playerIndex === 0 ? 1 : 0
        const opponent = currentSide.rounds[roundIndex][matchIndex * 2 + otherIndex]

        declareWin(currentPlayer, opponent, 1, 1)
      }

      if (nextRound === currentSide.rounds.length - 1) {
        finalMatch.value[side === 'left' ? 0 : 1] = currentPlayer
      }
    }
  }
}

function parOuImpar(numero: number) {
  return numero % 2 === 0 ? 1 : -1;
}

async function selectFinalWinner(player: CompetidoresTabela | null, declareWinner: boolean = true) {
  if (!player) return

  const confirm = await Swal.fire({
    title: `Deseja declarar ${player?.nome} como vencedor da final?`,
    showCancelButton: true,
    confirmButtonText: 'Sim',
    cancelButtonText: 'Não'
  })

  if (confirm.isConfirmed) {
    finaWin(player, declareWinner)
  }
}

function finaWin(player: CompetidoresTabela, declareWinner: boolean){
  finalWinner.value = player
  secondPlace.value = finalMatch.value.find(p => p !== player) || null

  if(declareWinner){
    declareWin(finalWinner.value, secondPlace.value!, 1, 1);
  }

  const thirdLeft = leftState.rounds[leftState.rounds.length - 2].find(p => p !== finalMatch.value[0])
  const thirdRight = rightState.rounds[rightState.rounds.length - 2].find(p => p !== finalMatch.value[1])

  thirdPlaces.value = [thirdLeft, thirdRight].filter(Boolean) as CompetidoresTabela[]
  showPodium.value = true
}

async function declareWin(currentWin: CompetidoresTabela, currentLoser: CompetidoresTabela | null, tipo: number, derrota: number)
{
  await axiosGet(route('competicao.competidor-vencedor-retorno'), {
    competidor_id: currentWin.id,
    categoria_id: currentWin.categoria_id,
    competicao_id: currentWin.competicao_id,
    vitorias: currentWin.vitorias,
    tipo: tipo
  });

  if(tipo == 1){
    currentWin.vitorias = Number(currentWin.vitorias) + 1;
  }else{
    currentWin.vitorias = Number(currentWin.vitorias) - 1;
  }

  if(currentLoser){
    await axiosGet(route('competicao.competidor-vencedor-retorno'), {
      competidor_id: currentLoser.id,
      categoria_id: currentLoser.categoria_id,
      competicao_id: currentLoser.competicao_id,
      vitorias: currentLoser.vitorias,
      derrota: derrota
    });
    currentLoser.derrota = derrota;
  }
}

onMounted(() => {
  initRounds()
})

const leftRounds = computed(() => leftState.rounds.slice(0, -1))
const rightRounds = computed(() => rightState.rounds.slice(0, -1))
</script>

<template>
  <div class="flex flex-col items-center bg-gray-900 min-h-screen px-4 py-6 sm:px-6">
    <h1 class="text-2xl font-bold mb-6 text-white text-center">Chave de Competição</h1>

    <!-- Container com scroll horizontal -->
    <div class="w-full overflow-x-auto max-w-full overflow-y-hidden pb-4">
      <div class="flex justify-center min-w-fit mx-auto gap-x-6">

        <!-- Lado Esquerdo -->
        <div class="flex">
          <div v-for="(round, rIndex) in leftRounds" :key="'left-' + rIndex"
            class="flex flex-col items-center mx-1 sm:mx-2"
            :style="{ marginTop: `${(2 ** rIndex - 1) * 16}px` }">
            <div v-for="(match, mIndex) in getMatches(round)" :key="'left-match-' + mIndex"
              class="flex flex-col justify-center items-center border border-white rounded-lg mb-6 w-40 sm:w-48 mt-auto mb-auto" :style="(rIndex == 0) ? 'margin-top: 10px' : ''">
              <div v-for="(player, pIndex) in match" :key="pIndex"
                class="w-full h-10 sm:h-12 flex items-center justify-center cursor-pointer text-white hover:bg-gray-700 text-xs sm:text-sm text-center"
                :class="{
                  'bg-green-700': (player?.vitorias ?? 0) > rIndex,
                  'opacity-50 pointer-events-none': (player?.derrota ?? 0) == 1,
                }" @click="handleClick('left', rIndex, mIndex, pIndex)">
                {{ player?.nome || '--' }}
              </div>
            </div>
          </div>
        </div>

        <!-- Final -->
        <div class="flex flex-col justify-center mx-2 sm:mx-4">
          <div
            class="w-40 sm:w-48 border border-yellow-500 rounded-lg p-2 mb-4 flex flex-col items-center text-center font-semibold text-yellow-500  mt-auto mb-auto">
            <div class="text-white mb-2">FINAL</div>
            <div v-if="finalMatch.length === 2">
              <div v-for="(player, index) in finalMatch" :key="index"
                class="w-full h-10 sm:h-12 flex items-center justify-center cursor-pointer text-white hover:bg-gray-700 text-xs sm:text-sm text-center"
                :class="{
                  'bg-green-700': finalWinner === player,
                  'opacity-50 pointer-events-none': finalWinner && finalWinner !== player,
                }" @click="selectFinalWinner(player)">
                {{ player?.nome || '--' }} 
              </div>
            </div>
          </div>
        </div>

        <!-- Lado Direito (reverso) -->
        <div class="flex flex-row-reverse">
          <div v-for="(round, rIndex) in rightRounds" :key="'right-' + rIndex"
            class="flex flex-col items-center mx-1 sm:mx-2"
            :style="{ marginTop: `${(2 ** rIndex - 1) * 16}px` }">
            <div v-for="(match, mIndex) in getMatches(round)" :key="'right-match-' + mIndex"
              class="flex flex-col justify-center items-center border border-white rounded-lg mb-6 w-40 sm:w-48 mt-auto mb-auto" :style="(rIndex == 0) ? 'margin-top: 10px' : ''">
              <div v-for="(player, pIndex) in match" :key="pIndex"
                class="w-full h-10 sm:h-12 flex items-center justify-center cursor-pointer text-white hover:bg-gray-700 text-xs sm:text-sm text-center"
                :class="{
                  'bg-green-700': (player?.vitorias ?? 0) > rIndex,
                  'opacity-50 pointer-events-none': (player?.derrota ?? 0) == 1,
                }" @click="handleClick('right', rIndex, mIndex, pIndex)">
                {{ player?.nome || '--' }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pódio -->
    <div v-if="showPodium" class="mt-8 w-full max-w-md px-2 sm:px-0">
      <h2 class="text-xl font-bold text-white mb-4 text-center">Pódio</h2>
      <table class="w-full text-white border border-white rounded-lg text-sm sm:text-base">
        <thead>
          <tr>
            <th class="border-b border-white p-2">Posição</th>
            <th class="border-b border-white p-2">Competidor</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="border-b border-white p-2">1º</td>
            <td class="border-b border-white p-2">{{ finalWinner?.nome || '--' }}</td>
          </tr>
          <tr>
            <td class="border-b border-white p-2">2º</td>
            <td class="border-b border-white p-2">{{ secondPlace?.nome || '--' }}</td>
          </tr>
          <tr v-for="(third, index) in thirdPlaces" :key="index">
            <td class="border-b border-white p-2">3º</td>
            <td class="border-b border-white p-2">{{ third?.nome || '--' }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="flex justify-end p-4">
      <NavLink
          :href="route('competicao.gerar-tabela-competicao', [competicao])"
          class="mr-1 items-center rounded-md border border-transparent bg-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-300 focus:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-stone-950 dark:bg-gray-300 dark:text-stone-950 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-gray-300 dark:active:bg-gray-300"
          :button="true"
      >
          Voltar
      </NavLink>
    </div>
  </div>
</template>


<style scoped>
::-webkit-scrollbar {
  height: 8px;
}
::-webkit-scrollbar-thumb {
  background: #555;
  border-radius: 4px;
}
</style>
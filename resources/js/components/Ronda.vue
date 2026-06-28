<template>
  <div>
    <div class="stats-bar">
      <div :class="['stat-card', filtroActivo === 'all' ? 'stat-active' : '']" data-filtro="all" @click="$emit('update:filtroActivo', 'all')">
        <div class="num" style="color: var(--azul);">{{ stats.total }}</div>
        <div class="lbl">Total</div>
      </div>
      <div :class="['stat-card', 'stat-verde', filtroActivo === 'funciona' ? 'stat-active' : '']" @click="$emit('update:filtroActivo', 'funciona')">
        <div class="num">{{ stats.funciona }}</div>
        <div class="lbl">Funcionan</div>
      </div>
      <div :class="['stat-card', 'stat-amarillo', filtroActivo === 'aislado' ? 'stat-active' : '']" @click="$emit('update:filtroActivo', 'aislado')">
        <div class="num">{{ stats.aislado }}</div>
        <div class="lbl">Aislados</div>
      </div>
      <div :class="['stat-card', 'stat-rojo', filtroActivo === 'no-funciona' ? 'stat-active' : '']" @click="$emit('update:filtroActivo', 'no-funciona')">
        <div class="num">{{ stats.noFunciona }}</div>
        <div class="lbl">No Funcionan</div>
      </div>
      <div :class="['stat-card', 'stat-gris', filtroActivo === 'no-hay' ? 'stat-active' : '']" @click="$emit('update:filtroActivo', 'no-hay')">
        <div class="num">{{ stats.noHay }}</div>
        <div class="lbl">Sin Llamado</div>
      </div>
      <div class="progress-bar">
        <div class="progress-track">
          <div class="progress-fill" :style="{ width: stats.porcentajeRevisados + '%' }"></div>
        </div>
        <div class="progress-label">{{ stats.porcentajeRevisados }}% ({{ stats.revisados }}/{{ stats.total }})</div>
      </div>
    </div>

    <div class="content">
      <div v-for="(seccion, seccionIndex) in seccionesFiltradas" :key="seccionIndex" class="seccion fade-in">
        <div class="seccion-header">
          <div class="seccion-titulo">{{ seccion.nombre }}</div>
          <div class="seccion-badge">{{ seccion.rooms.length }} {{ seccion.rooms.length === 1 ? 'habitación' : 'habitaciones' }}</div>
        </div>
        <div :class="['rooms-grid', compactMode ? 'compact' : '']">
          <div v-for="(room, roomIndex) in seccion.rooms" :key="room.id" :class="['room-card', room.estado, { 'room-card-next': nextRoomId === room.id, 'room-card-flash': flashingRoomId === room.id }]" @click="abrirModal(room)">
            <div class="room-nombre">{{ room.name }}</div>
            <div v-if="nextRoomId === room.id" class="room-next-badge">→ Siguiente</div>
            <div :class="['room-estado', room.estado]">
              {{ estadoIcono(room.estado) }} {{ estadoLabel(room.estado) }}
            </div>
            <div v-if="room.observacion" class="room-obs">{{ room.observacion }}</div>
            <div v-if="room.timestamp" class="room-tiempo">
              <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              {{ formatTime(room.timestamp) }}
            </div>

            <div v-if="room.type === 'baños'" class="room-bano" @click.stop>
              <div class="bano-row">
                <span class="bano-icon">🚽</span>
                <div class="bano-dots">
                  <span v-for="estado in estadosPosibles" 
                    :key="estado"
                    :class="['bano-dot', 'dot-' + estado, room.estado === estado ? 'active' : '']"
                    @click="cambiarEstadoBano(room, estado)"
                    :data-tip="estadoLabel(estado)">{{ estadoIcono(estado) }}</span>
                </div>
                <span v-if="room.observacion" class="bano-obs" :title="room.observacion">{{ room.observacion }}</span>
              </div>
              <div v-if="room.timestamp" class="bano-tiempo">{{ formatTime(room.timestamp) }}</div>
            </div>

            <div v-else-if="debeMostrarBano(room, seccion.rooms, roomIndex)" class="room-bano" @click.stop>
              <div class="bano-row">
                <span class="bano-icon">🚽</span>
                <div class="bano-dots">
                  <span v-for="estado in estadosPosibles" 
                    :key="estado"
                    :class="['bano-dot', 'dot-' + estado, banoAsociado(room)?.estado === estado ? 'active' : '']"
                    @click="cambiarEstadoBano(banoAsociado(room), estado)"
                    :data-tip="estadoLabel(estado)">{{ estadoIcono(estado) }}</span>
                </div>
                <span v-if="banoAsociado(room)?.observacion" class="bano-obs" :title="banoAsociado(room)?.observacion">{{ banoAsociado(room)?.observacion }}</span>
              </div>
              <div v-if="banoAsociado(room)?.timestamp" class="bano-tiempo">{{ formatTime(banoAsociado(room)?.timestamp) }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div style="padding: 0 1.5rem 2.5rem; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
      <div v-if="ronda?.completada" style="background: linear-gradient(135deg,#dcfce7,#bbf7d0); border: 1px solid #22c55e; border-radius: var(--radius-md); padding: 1rem 1.5rem; text-align: center; width: 100%; max-width: 420px; box-shadow: 0 4px 16px rgba(34,197,94,0.15);">
        <div style="font-size: 1.5rem; margin-bottom: 0.3rem;">✅</div>
        <div style="font-weight: 700; color: #15803d; font-size: 0.95rem;">Ronda completada</div>
      </div>
      <button v-if="!ronda?.completada" class="btn btn-green" id="btnFinalizarRonda" @click="finalizarRonda" style="min-width: 220px; justify-content: center; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.95rem; padding: 0.65rem 1.5rem;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        Finalizar Ronda
      </button>
      <button v-if="ronda?.completada" class="btn" id="btnReabrirRonda" @click="reabrirRonda" style="min-width: 220px; justify-content: center; display: inline-flex; align-items: center; gap: 0.4rem;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
        Reabrir Ronda
      </button>
      <button class="btn" @click="exportarExcel" style="min-width: 220px; justify-content: center; display: inline-flex; align-items: center; gap: 0.4rem;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        Exportar Excel
      </button>
    </div>

    <div :class="['modal-overlay', modalOpen ? 'open' : '']" @click.self="cerrarModal">
      <div class="modal">
        <h3>{{ modalRoom?.name }}</h3>
        <div class="sub">{{ modalRoom?.section }}</div>
        <div class="estado-grid">
          <div v-for="estado in estadosPosibles" 
            :key="estado"
            :class="['estado-opt', estadoSeleccionado === estado ? 'sel-' + estado : '']"
            @click="estadoSeleccionado = estado">
            {{ estadoIcono(estado) }} {{ estadoLabel(estado) }}
          </div>
        </div>
        <div class="obs-label">Observaciones (opcional)</div>
        <input class="obs-input" v-model="observacionModal" type="text" placeholder="Ej: Cable roto, en espera de repuesto..." @keydown.ctrl.enter="guardarCambio" @keydown.meta.enter="guardarCambio">
        <div class="modal-actions">
          <button class="btn" @click="cerrarModal">Cancelar</button>
          <button class="btn btn-primary" @click="guardarCambio" :disabled="loading">
            <span v-if="loading" class="spinner" style="width:14px;height:14px;border-width:2px;"></span>
            <span v-else>Guardar</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, nextTick } from 'vue'
import { useStore } from '../store'
import { api } from '../api'
import { refreshHistorial, refreshHistorialDebounced } from '../utils.js'
import * as XLSX from 'xlsx'

const props = defineProps({
  filtroPiso: { type: String, default: 'all' },
  subtabActivo: { type: String, default: 'llamados' },
  filtroActivo: { type: String, default: 'all' },
  busqueda: { type: String, default: '' }
})

const emit = defineEmits(['update:filtroActivo'])

const store = useStore()

const ronda = computed(() => store.state.ronda)
const rooms = computed(() => store.state.rooms)

watch(() => props.filtroActivo, async () => {
  await nextTick()
  const wrap = document.querySelector('.content-wrap')
  if (wrap) {
    const rect = wrap.getBoundingClientRect()
    if (rect.top < 106) {
      wrap.scrollBy({ top: rect.top - 106, behavior: 'smooth' })
    }
  }
})

const compactMode = ref(false)
const modalOpen = ref(false)
const modalRoom = ref(null)
const estadoSeleccionado = ref('funciona')
const observacionModal = ref('')
const loading = ref(false)
const flashingRoomId = ref(null)

const estadosPosibles = ['funciona', 'no-funciona', 'aislado', 'no-hay']

const ordenSecciones = ['Piso 9', 'Piso 8', 'Piso 7', 'Piso 6', 'Piso 5', 'Piso 2', 'Piso 1']

const getEstructura = (filtroPisoOverride) => {
  const piso = filtroPisoOverride || props.filtroPiso
  const seccionesMap = {}
  rooms.value.forEach(room => {
    const pisoMatch = piso === 'all' || room.section.startsWith(piso + ' ')
    if (!pisoMatch) return
    if (!seccionesMap[room.section]) {
      seccionesMap[room.section] = { nombre: room.section, rooms: [] }
    }
    seccionesMap[room.section].rooms.push(room)
  })
  return Object.values(seccionesMap)
    .filter(seccion => 
      props.subtabActivo === 'llamados' 
        ? !seccion.nombre.includes('Baños') 
        : seccion.nombre.includes('Baños')
    )
    .sort((a, b) => {
      const ai = ordenSecciones.findIndex(o => a.nombre.startsWith(o))
      const bi = ordenSecciones.findIndex(o => b.nombre.startsWith(o))
      return (ai === -1 ? 999 : ai) - (bi === -1 ? 999 : bi)
    })
}

const stats = computed(() => {
  const roomsFiltradas = getEstructura().flatMap(s => s.rooms)
  const counts = { funciona: 0, noFunciona: 0, aislado: 0, noHay: 0 }
  let revisados = 0
  roomsFiltradas.forEach(room => {
    if (room.timestamp) revisados++
    switch (room.estado) {
      case 'funciona': counts.funciona++; break
      case 'no-funciona': counts.noFunciona++; break
      case 'aislado': counts.aislado++; break
      case 'no-hay': counts.noHay++; break
    }
  })
  return {
    ...counts,
    total: roomsFiltradas.length,
    revisados,
    porcentajeRevisados: roomsFiltradas.length ? Math.round((revisados / roomsFiltradas.length) * 100) : 0
  }
})

const seccionesFiltradas = computed(() => {
  return getEstructura().map(seccion => ({
    ...seccion,
    rooms: seccion.rooms.filter(room => {
      const estadoMatch = props.filtroActivo === 'all' || room.estado === props.filtroActivo
      const busquedaMatch = !props.busqueda || 
        room.name.toLowerCase().includes(props.busqueda.toLowerCase()) || 
        room.room_id.toLowerCase().includes(props.busqueda.toLowerCase())
      return estadoMatch && busquedaMatch
    })
  })).filter(s => s.rooms.length > 0)
})

const nextRoomId = computed(() => {
  const allRooms = seccionesFiltradas.value.flatMap(s => s.rooms)
  for (const room of allRooms) {
    if (!room.timestamp && room.id !== modalRoom.value?.id) {
      return room.id
    }
  }
  return null
})

const estadoLabel = (estado) => {
  const labels = {
    'funciona': 'Funciona',
    'no-funciona': 'No Funciona',
    'aislado': 'Aislado',
    'no-hay': 'No Hay'
  }
  return labels[estado] || estado
}

const estadoIcono = (estado) => {
  const iconos = {
    'funciona': '✅',
    'no-funciona': '❌',
    'aislado': '⚡',
    'no-hay': '➖'
  }
  return iconos[estado] || ''
}

const formatTime = (ts) => {
  return new Date(ts).toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' })
}

const banoAsociado = (room) => {
  if (room.type !== 'llamados') return null
  if (!room.section.startsWith('Piso 7') && !room.section.startsWith('Piso 8')) return null
  const numero = room.room_id.replace(/[A-Z]/g, '')
  const banoId = room.section.startsWith('Piso 7') ? `B7-${numero}` : `B8-${numero}`
  return rooms.value.find(r => r.room_id === banoId)
}

const debeMostrarBano = (room, seccionRooms, roomIndex) => {
  const bano = banoAsociado(room)
  if (!bano) return false
  for (let i = 0; i < roomIndex; i++) {
    const prevRoom = seccionRooms[i]
    const prevBano = banoAsociado(prevRoom)
    if (prevBano && prevBano.id === bano.id) {
      return false
    }
  }
  return true
}

const cambiarEstadoBano = async (bano, estado) => {
  if (!bano || !ronda.value) return
  loading.value = true
  try {
    await api.updateRoomState(ronda.value.id, bano.id, { estado, observacion: bano.observacion || '' })
    const roomIndex = rooms.value.findIndex(r => r.id === bano.id)
    if (roomIndex !== -1) {
      store.state.rooms[roomIndex] = {
        ...rooms.value[roomIndex],
        estado,
        timestamp: new Date().toISOString()
      }
    }
    refreshHistorialDebounced()
  } catch (err) {
    store.showToast('Error al cambiar estado del baño', 'error')
  } finally {
    loading.value = false
  }
}

const abrirModal = (room) => {
  modalRoom.value = room
  estadoSeleccionado.value = room.estado
  observacionModal.value = room.observacion || ''
  modalOpen.value = true
}

const cerrarModal = () => {
  modalOpen.value = false
  modalRoom.value = null
  estadoSeleccionado.value = 'funciona'
  observacionModal.value = ''
}

const avanzarSiguiente = (savedRoomId) => {
  const allRooms = seccionesFiltradas.value.flatMap(s => s.rooms)
  const currentIndex = allRooms.findIndex(r => r.id === savedRoomId)
  for (let i = currentIndex + 1; i < allRooms.length; i++) {
    if (!allRooms[i].timestamp) {
      abrirModal(allRooms[i])
      return
    }
  }
}

const guardarCambio = async () => {
  if (!modalRoom.value || !ronda.value) return
  loading.value = true
  const savedRoomId = modalRoom.value.id
  const savedEstado = estadoSeleccionado.value
  try {
    await api.updateRoomState(ronda.value.id, savedRoomId, { 
      estado: savedEstado, 
      observacion: observacionModal.value 
    })
    const roomIndex = rooms.value.findIndex(r => r.id === savedRoomId)
    if (roomIndex !== -1) {
      store.state.rooms[roomIndex] = {
        ...rooms.value[roomIndex],
        estado: savedEstado,
        observacion: observacionModal.value,
        timestamp: new Date().toISOString()
      }
    }
    cerrarModal()
    refreshHistorialDebounced()
    store.showToast('Cambio guardado correctamente', 'success')

    if (savedEstado === 'funciona') {
      flashingRoomId.value = savedRoomId
      await nextTick()
      await new Promise(resolve => setTimeout(resolve, 600))
      flashingRoomId.value = null
    }
  } catch (err) {
    store.showToast('Error al guardar cambio', 'error')
  } finally {
    loading.value = false
  }
}

const finalizarRonda = async () => {
  if (!ronda.value) return
  try {
    const response = await api.finalizeRonda(ronda.value.id)
    store.setRonda(response.ronda)
    store.showToast('Ronda finalizada con éxito', 'success')
  } catch (err) {
    store.showToast('Error al finalizar ronda', 'error')
  }
}

const exportarExcel = () => {
  const todas = getEstructura().flatMap(s => s.rooms)
  const datos = todas.map(r => [
    r.section,
    r.name,
    r.room_id,
    r.type === 'baños' ? 'Baños' : 'Llamados',
    estadoLabel(r.estado),
    r.observacion || '',
    r.timestamp ? formatTime(r.timestamp) : ''
  ])
  datos.unshift(['Sección', 'Habitación', 'ID', 'Tipo', 'Estado', 'Observación', 'Hora'])
  const ws = XLSX.utils.aoa_to_sheet(datos)
  ws['!cols'] = [{ wch: 34 }, { wch: 22 }, { wch: 14 }, { wch: 10 }, { wch: 14 }, { wch: 30 }, { wch: 10 }]
  const wb = XLSX.utils.book_new()
  XLSX.utils.book_append_sheet(wb, ws, 'Ronda')
  const fecha = new Date().toISOString().split('T')[0]
  XLSX.writeFile(wb, `Ronda_${fecha}.xlsx`)
}

const reabrirRonda = async () => {
  if (!ronda.value) return
  try {
    const response = await api.reopenRonda(ronda.value.id)
    store.setRonda(response.ronda)
    store.showToast('Ronda reabierta', 'success')
  } catch (err) {
    store.showToast('Error al reabrir ronda', 'error')
  }
}
</script>

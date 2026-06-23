<template>
  <div class="analisis">
    <div class="analisis-toolbar">
      <button class="btn btn-primary btn-seleccionar" @click="abrirModal">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 21l-4.35-4.35M11 4a7 7 0 1 0 0 14 7 7 0 0 0 0-14z"/><path d="M16 5l3 3"/><path d="M19 2l3 3"/></svg>
        Seleccionar habitación
      </button>
    </div>

    <div v-if="habitacionSeleccionada" class="analisis-tag-bar">
      <span class="tag-label">Has seleccionado:</span>
      <span class="search-tag">{{ habitacionSeleccionada }}</span>
      <button class="btn-trash" @click="limpiarSeleccion" title="Limpiar selección">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
      </button>
    </div>

    <div :class="['modal-overlay', modalOpen ? 'open' : '']" @click.self="cerrarModal">
      <div class="modal analisis-modal">
        <div class="modal-header">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
          <span>Seleccionar habitación</span>
          <span class="modal-hab-count">{{ totalHabitaciones }} hab.</span>
          <button class="modal-close" @click="cerrarModal">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="modal-filtros">
          <div class="toggle-group">
            <button :class="['toggle-btn', modalFiltroTipo === 'llamados' ? 'active' : '']" @click="modalFiltroTipo = 'llamados'">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
              Llamados
            </button>
            <button :class="['toggle-btn', modalFiltroTipo === 'baños' ? 'active' : '']" @click="modalFiltroTipo = 'baños'">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 8h16"/><path d="M6 12V8"/><path d="M10 12V8"/><path d="M14 12V8"/><path d="M18 12V8"/><path d="M4 16h16"/><path d="M2 20h20"/></svg>
              Baños
            </button>
          </div>
          <div class="filter-select-wrap">
            <span class="filter-label">Piso</span>
            <select class="filter-select" v-model="modalFiltroPiso">
              <option value="all">Todos</option>
              <option value="Piso 7">Piso 7</option>
              <option value="Piso 8">Piso 8</option>
              <option value="UCI">UCI</option>
              <option value="UCIN">UCIN</option>
            </select>
          </div>
        </div>
        <div class="modal-body">
          <div class="modal-busqueda">
            <svg class="modal-busqueda-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input class="modal-busqueda-input" v-model="busqueda" placeholder="Buscar habitación..." />
          </div>
          <div v-for="(seccion, i) in modalSecciones" :key="i" class="modal-seccion">
            <div class="modal-seccion-titulo">
              {{ seccion.nombre }}
              <span class="modal-seccion-count">{{ seccion.rooms.length }}</span>
            </div>
            <div class="modal-rooms">
              <div v-for="room in seccion.rooms" :key="room.id" :class="['modal-room', room.estado]" @click="seleccionarHabitacion(room.name)">
                <span class="modal-room-name">{{ room.name }}</span>
                <span :class="['chip', room.estado]">{{ estadoLabel(room.estado) }}</span>
              </div>
            </div>
          </div>
          <div v-if="!modalSecciones.length" class="modal-empty">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:#cbd5e1"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <span>No hay habitaciones con estos filtros</span>
          </div>
        </div>
      </div>
    </div>



    <div class="timeline-wrap">
      <div class="timeline-card">
        <template v-if="habitacionSeleccionada">
          <div class="timeline-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <span>Estado diario — {{ habitacionSeleccionada }}</span>
            <span class="timeline-mes">{{ mesNombre }}</span>
            <button class="btn-resumen" @click="abrirResumen">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
              Resumen
            </button>
          </div>
          <div class="timeline-grid">
            <div v-for="d in timelineData" :key="d.day" class="timeline-col">
              <div :class="['timeline-state', d.state ? 'state-' + d.state : '']" @click="abrirDetalle(d)">
                {{ d.label }}
                <div class="timeline-tooltip">
                  <div class="tt-header">Día {{ d.day }} — <span :class="'tt-state-' + d.state">{{ estadoTextoCorto(d.state) }}</span></div>
                  <div v-if="d.detalle?.hora" class="tt-row">Hora: {{ d.detalle.hora.includes('T') ? d.detalle.hora.split('T')[1]?.split('.')[0] : d.detalle.hora }}</div>
                  <div v-if="d.detalle?.anterior" class="tt-row">Anterior: {{ d.detalle.anterior }}</div>
                  <div v-if="d.detalle?.observacion" class="tt-row tt-obs">{{ d.detalle.observacion }}</div>
                </div>
              </div>
              <div class="timeline-day">{{ d.day }}</div>
            </div>
          </div>
          <div class="timeline-legend">
            <span><span class="legend-dot state-F"></span> F = Funciona</span>
            <span><span class="legend-dot state-M"></span> M = No funciona</span>
            <span><span class="legend-dot state-A"></span> A = Aislado</span>
            <span><span class="legend-dot state-NH"></span> NH = No hay</span>
            <span><span style="background:#e2e8f0;width:10px;height:10px;border-radius:2px;display:inline-block;margin-right:3px;"></span> — = Sin datos</span>
          </div>
        </template>
        <div v-else class="timeline-empty">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:#cbd5e1"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span>Seleccioná una habitación para ver su estado diario</span>
        </div>
      </div>
    </div>

    <div :class="['modal-overlay', detalleModalOpen ? 'open' : '']" @click.self="cerrarDetalleModal">
      <div class="resumen-card">
        <button class="resumen-close" @click="cerrarDetalleModal">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
        </button>

        <div class="resumen-top">
          <div class="resumen-room">
            <div class="resumen-room-icon">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/><path d="M8 6h8"/><path d="M8 10h8"/><path d="M8 14h4"/></svg>
            </div>
            <div>
              <div class="resumen-room-sub">Habitación</div>
              <div class="resumen-room-num">{{ numeroHabitacion }}</div>
            </div>
          </div>
          <div :class="['resumen-chip', 'chip-' + (detalleDia?.state || 'NH')]">
            <span class="resumen-chip-letter">{{ detalleDia?.label || '—' }}</span>
            <span>{{ estadoTextoCorto(detalleDia?.state) || 'Sin datos' }}</span>
          </div>
        </div>

        <div class="resumen-mes">{{ mesNombre }}</div>

        <div class="resumen-chart">
          <div class="resumen-chart-title">Distribución del mes</div>
          <div class="resumen-chart-bars">
            <div class="resumen-chart-row">
              <span class="resumen-chart-label">Funciona</span>
              <div class="resumen-chart-track">
                <div class="resumen-chart-fill" style="background:#22c55e" :style="{ width: pctF + '%' }"></div>
              </div>
              <span class="resumen-chart-count">{{ estadosCount.F }}<span class="resumen-chart-pct"> ({{ pctF }}%)</span></span>
            </div>
            <div class="resumen-chart-row">
              <span class="resumen-chart-label">No funciona</span>
              <div class="resumen-chart-track">
                <div class="resumen-chart-fill" style="background:#ef4444" :style="{ width: pctM + '%' }"></div>
              </div>
              <span class="resumen-chart-count">{{ estadosCount.M }}<span class="resumen-chart-pct"> ({{ pctM }}%)</span></span>
            </div>
            <div class="resumen-chart-row">
              <span class="resumen-chart-label">Aislado</span>
              <div class="resumen-chart-track">
                <div class="resumen-chart-fill" style="background:#f59e0b" :style="{ width: pctA + '%' }"></div>
              </div>
              <span class="resumen-chart-count">{{ estadosCount.A }}<span class="resumen-chart-pct"> ({{ pctA }}%)</span></span>
            </div>
            <div class="resumen-chart-row">
              <span class="resumen-chart-label">Sin llamado</span>
              <div class="resumen-chart-track">
                <div class="resumen-chart-fill" style="background:#94a3b8" :style="{ width: pctNH + '%' }"></div>
              </div>
              <span class="resumen-chart-count">{{ estadosCount.NH }}<span class="resumen-chart-pct"> ({{ pctNH }}%)</span></span>
            </div>
          </div>
        </div>

        <div v-if="topObservaciones.length" class="resumen-topobs">
          <div class="resumen-topobs-text">{{ topObservaciones[0].text }}</div>
          <div class="resumen-topobs-footer">
            <span class="resumen-topobs-count">{{ topObservaciones[0].count }} veces</span>
            <span v-if="topObservaciones.length > 1" class="resumen-topobs-mas" @click="showAllObs = !showAllObs">
              {{ showAllObs ? 'Ocultar' : 'Ver todas' }} ({{ topObservaciones.length }})
            </span>
          </div>
          <div v-if="showAllObs" class="resumen-obs-tags" style="margin-top:8px">
            <span v-for="(obs, i) in topObservaciones.slice(1)" :key="i" class="resumen-obs-tag">
              {{ obs.text }}
              <span class="resumen-obs-count">{{ obs.count }}</span>
            </span>
          </div>
        </div>

        <div v-if="detalleDia?.detalle?.observacion" class="resumen-nota">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          {{ detalleDia.detalle.observacion }}
        </div>

        <div v-if="!detalleDia?.detalle?.hora && !detalleDia?.detalle?.anterior" class="resumen-empty">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span>Sin registros este día</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useStore } from '../store'

const store = useStore()

const busqueda = ref('')
const habitacionSeleccionada = ref(null)
const modalOpen = ref(false)
const modalFiltroTipo = ref('llamados')
const modalFiltroPiso = ref('all')

const limpiarSeleccion = () => {
  habitacionSeleccionada.value = null
  busqueda.value = ''
}

const abrirModal = () => { modalOpen.value = true }

const seleccionarHabitacion = (name) => {
  habitacionSeleccionada.value = name
  busqueda.value = name
  modalOpen.value = false
}

const cerrarModal = () => { modalOpen.value = false }

const detalleModalOpen = ref(false)
const detalleDia = ref(null)
const showAllObs = ref(false)

const abrirDetalle = (d) => {
  detalleDia.value = d
  detalleModalOpen.value = true
}

const abrirResumen = () => {
  if (detalleModalOpen.value) {
    cerrarDetalleModal()
  } else {
    if (!detalleDia.value) {
      detalleDia.value = { day: new Date().getDate(), state: null, label: '—', detalle: null }
    }
    detalleModalOpen.value = true
  }
}

const cerrarDetalleModal = () => {
  detalleModalOpen.value = false
  detalleDia.value = null
}

const estadoTexto = (s) => {
  if (!s) return ''
    const map = { 'funciona':'Funcionaba','no-funciona':'No funcionaba','aislado':'Aislado','no-hay':'Sin llamado' }
  return map[s] || s
}

const estadoTextoCorto = (s) => {
  if (!s) return ''
  const map = { 'F':'Funciona','M':'No funciona','A':'Aislado','NH':'No hay' }
  return map[s] || s
}

const numeroHabitacion = computed(() => {
  if (!habitacionSeleccionada.value) return '—'
  const m = habitacionSeleccionada.value.match(/\d+/)
  return m ? m[0] : habitacionSeleccionada.value
})



const getHistorialDia = (roomName, day) => {
  const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`
  return store.state.historial.filter(h => h.room_name === roomName && h.date === dateStr)
    .sort((a, b) => new Date(a.created_at || a.date) - new Date(b.created_at || b.date))
}

const pctF = computed(() => Math.round((estadosCount.value.F / daysInMonth) * 100) || 0)
const pctM = computed(() => Math.round((estadosCount.value.M / daysInMonth) * 100) || 0)
const pctA = computed(() => Math.round((estadosCount.value.A / daysInMonth) * 100) || 0)
const pctNH = computed(() => Math.round((estadosCount.value.NH / daysInMonth) * 100) || 0)

const prevMonth = computed(() => month === 0 ? 11 : month - 1)
const prevYear = computed(() => month === 0 ? year - 1 : year)

const historialRoomMes = computed(() => {
  if (!habitacionSeleccionada.value) return []
  return store.state.historial.filter(h => {
    const d = new Date(h.date + 'T12:00:00')
    return h.room_name === habitacionSeleccionada.value &&
      d.getFullYear() === year && d.getMonth() === month
  })
})

const historialRoomMesAnterior = computed(() => {
  if (!habitacionSeleccionada.value) return []
  return store.state.historial.filter(h => {
    const d = new Date(h.date + 'T12:00:00')
    return h.room_name === habitacionSeleccionada.value &&
      d.getFullYear() === prevYear.value && d.getMonth() === prevMonth.value
  })
})

const cambiosEsteMes = computed(() => historialRoomMes.value.length)
const cambiosMesAnterior = computed(() => historialRoomMesAnterior.value.length)
const cambiosDiff = computed(() => cambiosEsteMes.value - cambiosMesAnterior.value)

const incidenciasEsteMes = computed(() => historialRoomMes.value.filter(h => h.nuevo === 'no-funciona').length)
const incidenciasMesAnterior = computed(() => historialRoomMesAnterior.value.filter(h => h.nuevo === 'no-funciona').length)
const incidenciasDiff = computed(() => incidenciasEsteMes.value - incidenciasMesAnterior.value)

const topObservaciones = computed(() => {
  const obs = historialRoomMes.value
    .filter(h => h.observacion && h.observacion.trim())
    .map(h => h.observacion.trim().charAt(0).toUpperCase() + h.observacion.trim().slice(1))
  const counts = {}
  obs.forEach(o => { counts[o] = (counts[o] || 0) + 1 })
  return Object.entries(counts)
    .sort((a, b) => b[1] - a[1])
    .slice(0, 5)
    .map(([text, count]) => ({ text, count }))
})

const ultimaActualizacion = computed(() => {
  const now = new Date()
  return now.toLocaleDateString('es-CO', { day: '2-digit', month: '2-digit', year: 'numeric' }) + ' ' +
    now.toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' })
})

const now = new Date()
const year = now.getFullYear()
const month = now.getMonth()
const daysInMonth = new Date(year, month + 1, 0).getDate()
const mesNombre = new Date(year, month).toLocaleDateString('es-CO', { month: 'long', year: 'numeric' })

const timelineData = computed(() => {
  const roomName = habitacionSeleccionada.value
  if (!roomName) return []

  const historial = store.state.historial.filter(h => h.room_name === roomName)
  const stateMap = { 'funciona': 'F', 'no-funciona': 'M', 'aislado': 'A', 'no-hay': 'NH' }
  const data = []

  for (let d = 1; d <= daysInMonth; d++) {
    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`
    const cambiosDia = historial.filter(h => h.date === dateStr).sort((a, b) => a.id - b.id)
    const ultimoCambio = cambiosDia.length ? cambiosDia[cambiosDia.length - 1] : null
    let ultimoEstado = ultimoCambio?.nuevo || null

    data.push({
      day: d,
      state: ultimoEstado ? stateMap[ultimoEstado] : null,
      label: ultimoEstado ? stateMap[ultimoEstado] : '—',
      rawState: ultimoEstado || null,
      detalle: ultimoCambio ? {
        hora: ultimoCambio.created_at || ultimoCambio.date,
        observacion: ultimoCambio.observacion || null,
        anterior: ultimoCambio.anterior || null
      } : null
    })
  }

  return data
})

const estadosCount = computed(() => {
  const counts = { F: 0, M: 0, A: 0, NH: 0 }
  timelineData.value.forEach(d => {
    if (d.state && counts[d.state] !== undefined) counts[d.state]++
  })
  return counts
})

const ordenSecciones = ['Piso 7', 'Piso 8', 'UCI', 'UCIN']

const modalSecciones = computed(() => {
  const rooms = store.state.rooms
  const seccionesMap = {}

  const q = busqueda.value.toLowerCase().trim()
  rooms.forEach(room => {
    if (q && !room.name.toLowerCase().includes(q) && !room.room_id?.toLowerCase().includes(q)) return
    const pisoMatch = modalFiltroPiso.value === 'all' || room.section.startsWith(modalFiltroPiso.value + ' ')
    if (!pisoMatch) return
    if (modalFiltroTipo.value === 'llamados' && room.section.includes('Baños')) return
    if (modalFiltroTipo.value === 'baños' && !room.section.includes('Baños')) return

    if (!seccionesMap[room.section]) {
      seccionesMap[room.section] = { nombre: room.section, rooms: [] }
    }
    seccionesMap[room.section].rooms.push(room)
  })

  return Object.values(seccionesMap).sort((a, b) => {
    const ai = ordenSecciones.findIndex(o => a.nombre.startsWith(o))
    const bi = ordenSecciones.findIndex(o => b.nombre.startsWith(o))
    return (ai === -1 ? 999 : ai) - (bi === -1 ? 999 : bi)
  })
})

const totalHabitaciones = computed(() => {
  return modalSecciones.value.reduce((sum, s) => sum + s.rooms.length, 0)
})

const estadoLabel = (estado) => {
  const map = {
    'funciona': 'Funciona',
    'no-funciona': 'No Funciona',
    'aislado': 'Aislado',
    'no-hay': 'Sin Llamado'
  }
  return map[estado] || estado
}
</script>

<style scoped>
.analisis {
  padding: 1.5rem;
}

.analisis-toolbar {
  margin-bottom: 1.25rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.search-tag {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  background: linear-gradient(135deg, #ede9fe, #ddd6fe);
  color: #6d28d9;
  font-size: 0.78rem;
  font-weight: 600;
  padding: 0.35rem 0.75rem;
  border-radius: var(--radius-sm);
  white-space: nowrap;
}

.btn-seleccionar {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.55rem 1rem;
  font-size: 0.82rem;
  font-weight: 600;
  white-space: nowrap;
  flex-shrink: 0;
}

.analisis-tag-bar {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

/* Modal overlay & modal (same style as Ronda modal) */
.modal-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.45);
  backdrop-filter: blur(4px);
  z-index: 1000;
  align-items: center;
  justify-content: center;
}

.modal-overlay.open {
  display: flex;
}

.modal.analisis-modal {
  background: var(--surface);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-xl);
  width: 680px;
  max-width: 94vw;
  max-height: 80vh;
  display: flex;
  flex-direction: column;
  animation: modalIn 0.2s ease;
}

@keyframes modalIn {
  from { opacity: 0; transform: scale(0.95) translateY(10px); }
  to { opacity: 1; transform: scale(1) translateY(0); }
}

.modal-header {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 1rem 1.25rem;
  border-bottom: 1px solid var(--border);
  font-size: 0.9rem;
  font-weight: 700;
  color: var(--text);
}

.modal-header svg {
  color: var(--azul);
  flex-shrink: 0;
}

.modal-hab-count {
  margin-left: auto;
  font-size: 0.72rem;
  font-weight: 500;
  color: var(--text2);
  background: var(--surface3);
  border: 1px solid var(--border);
  border-radius: 5px;
  padding: 0.1rem 0.5rem;
}

.modal-close {
  background: none;
  border: none;
  color: var(--text2);
  cursor: pointer;
  padding: 4px;
  border-radius: 4px;
  display: flex;
  margin-left: 0.25rem;
  transition: all 0.2s ease;
}

.modal-close:hover {
  color: var(--text);
  background: var(--surface3);
}

.modal-filtros {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem 1.25rem;
  border-bottom: 1px solid var(--border);
  background: var(--surface3);
}

.modal-filtros .toggle-group {
  display: flex;
  background: var(--surface);
  border-radius: var(--radius-sm);
  padding: 2px;
  border: 1px solid var(--border);
  flex-shrink: 0;
}

.modal-filtros .toggle-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  padding: 0.35rem 0.6rem;
  font-size: 0.75rem;
  font-weight: 600;
  border: none;
  background: transparent;
  color: var(--text2);
  cursor: pointer;
  border-radius: 4px;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.modal-filtros .toggle-btn:hover {
  color: var(--text);
}

.modal-filtros .toggle-btn.active {
  background: var(--surface);
  color: var(--azul);
  box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.modal-filtros .filter-select-wrap {
  display: flex;
  align-items: center;
  gap: 0.35rem;
  flex-shrink: 0;
}

.modal-filtros .filter-label {
  font-size: 0.7rem;
  font-weight: 600;
  color: var(--text2);
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

.modal-filtros .filter-select {
  padding: 0.35rem 1.5rem 0.35rem 0.5rem;
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  background: var(--surface);
  font-size: 0.78rem;
  font-family: inherit;
  color: var(--text);
  cursor: pointer;
  outline: none;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23999' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 6px center;
}

.modal-filtros .filter-select:focus {
  border-color: var(--azul);
  box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
}

.modal-busqueda {
  position: relative;
  margin-bottom: 0.75rem;
}

.modal-busqueda-icon {
  position: absolute;
  left: 10px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text2);
  pointer-events: none;
}

.modal-busqueda-input {
  width: 100%;
  padding: 0.55rem 0.75rem 0.55rem 2rem;
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  background: var(--surface3);
  font-size: 0.82rem;
  font-family: inherit;
  color: var(--text);
  outline: none;
  box-sizing: border-box;
  transition: border-color 0.2s ease, background 0.2s ease;
}

.modal-busqueda-input:focus {
  border-color: var(--azul);
  background: var(--surface);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
}

.tag-label {
  font-size: 0.78rem;
  font-weight: 500;
  color: var(--text2);
  white-space: nowrap;
}

.btn-trash {
  background: none;
  border: none;
  color: #dc2626;
  cursor: pointer;
  padding: 6px;
  border-radius: var(--radius-sm);
  display: flex;
  transition: all 0.2s ease;
  opacity: 0.6;
}

.btn-trash:hover {
  opacity: 1;
  background: rgba(220, 38, 38, 0.08);
}

.modal-body {
  padding: 1rem 1.25rem;
  overflow-y: auto;
  flex: 1;
}

.modal-seccion {
  margin-bottom: 1rem;
}

.modal-seccion:last-child {
  margin-bottom: 0;
}

.modal-seccion-titulo {
  font-size: 0.72rem;
  font-weight: 700;
  color: var(--text2);
  text-transform: uppercase;
  letter-spacing: 0.3px;
  margin-bottom: 0.4rem;
  display: flex;
  align-items: center;
  gap: 0.35rem;
}

.modal-seccion-count {
  font-size: 0.65rem;
  font-weight: 600;
  color: var(--text2);
  background: var(--surface3);
  border-radius: 4px;
  padding: 0.05rem 0.4rem;
}

.modal-rooms {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
  gap: 0.35rem;
}

.modal-room {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.4rem 0.6rem;
  border-radius: var(--radius-sm);
  border: 1px solid var(--border);
  background: var(--surface);
  font-size: 0.78rem;
  cursor: pointer;
  transition: all 0.15s ease;
}

.modal-room:hover {
  background: var(--surface3);
  border-color: var(--border2);
  transform: translateY(-1px);
  box-shadow: var(--shadow-sm);
}

.modal-room.funciona { border-left: 3px solid #22c55e; }
.modal-room.no-funciona { border-left: 3px solid #ef4444; }
.modal-room.aislado { border-left: 3px solid #f59e0b; }
.modal-room.no-hay { border-left: 3px solid #9ca3af; }

.modal-room-name {
  color: var(--text);
  font-weight: 500;
}

.modal-empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  padding: 2rem;
  color: var(--text2);
  font-size: 0.85rem;
}

.analisis-header {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 1rem;
}

.btn-outline {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.5rem 0.9rem;
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  background: var(--surface);
  color: var(--text2);
  font-size: 0.78rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-outline:hover {
  border-color: var(--border2);
  color: var(--text);
  background: var(--surface3);
}

.analytics-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
  gap: 1rem;
}

.analytics-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 1.5rem;
  box-shadow: var(--shadow-sm);
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.analytics-card-title {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  font-size: 0.88rem;
  font-weight: 700;
  color: var(--text);
}

.analytics-icon {
  width: 34px;
  height: 34px;
  border-radius: var(--radius-md);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.analytics-metrics {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.75rem;
}

.analytics-metric {
  display: flex;
  flex-direction: column;
  gap: 0.15rem;
}

.analytics-label {
  font-size: 0.68rem;
  font-weight: 500;
  color: var(--text2);
  text-transform: uppercase;
  letter-spacing: 0.2px;
}

.analytics-value {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--text);
  line-height: 1.2;
}

.analytics-row {
  display: flex;
  align-items: center;
  gap: 0.3rem;
}

.analytics-change {
  font-size: 0.7rem;
  font-weight: 600;
}

.analytics-change.up {
  color: #16a34a;
}

.analytics-change.down {
  color: #dc2626;
}

.analytics-arrow {
  font-size: 0.65rem;
}

.sparkline {
  margin-top: 0.25rem;
  flex-shrink: 0;
}

.timeline-wrap {
  margin-top: 0;
}

.timeline-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 1.5rem;
  box-shadow: var(--shadow-sm);
}

.timeline-title {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.82rem;
  font-weight: 700;
  color: var(--text);
  margin-bottom: 1.25rem;
}

.timeline-title svg {
  color: var(--azul);
  flex-shrink: 0;
}

.timeline-mes {
  margin-left: auto;
  font-size: 0.72rem;
  font-weight: 500;
  color: var(--text2);
  background: var(--surface3);
  border: 1px solid var(--border);
  border-radius: 5px;
  padding: 0.15rem 0.55rem;
  text-transform: capitalize;
}

.timeline-grid {
  display: grid;
  grid-template-columns: repeat(31, 1fr);
  gap: 2px;
}

.timeline-col {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 2px;
}

.timeline-state {
  width: 100%;
  text-align: center;
  font-size: 0.65rem;
  font-weight: 700;
  padding: 3px 0;
  border-radius: 3px;
  line-height: 1;
}

.timeline-state.state-F {
  background: #dcfce7;
  color: #15803d;
}

.timeline-state.state-M {
  background: #fecaca;
  color: #dc2626;
}

.timeline-state.state-A {
  background: #fef3c7;
  color: #b45309;
}

.timeline-state.state-NH {
  background: #f1f5f9;
  color: #64748b;
}

.timeline-state:not(.state-F):not(.state-M):not(.state-A):not(.state-NH) {
  background: transparent;
  color: #cbd5e1;
}

.timeline-state {
  cursor: pointer;
  transition: transform 0.15s, box-shadow 0.15s;
}

.timeline-state:hover {
  transform: scale(1.08);
  box-shadow: 0 2px 8px rgba(0,0,0,0.12);
  z-index: 2;
  position: relative;
}


.timeline-state {
  position: relative;
}

.timeline-tooltip {
  display: none;
  position: absolute;
  bottom: calc(100% + 6px);
  left: 50%;
  transform: translateX(-50%);
  background: #fff;
  color: #1e293b;
  font-size: 0.65rem;
  font-weight: 500;
  padding: 0.45rem 0.6rem;
  border-radius: 6px;
  white-space: nowrap;
  z-index: 50;
  box-shadow: 0 4px 16px rgba(0,0,0,0.15);
  border: 1px solid #e2e8f0;
  line-height: 1.5;
  pointer-events: none;
  flex-direction: column;
  align-items: flex-start;
}

.timeline-tooltip::after {
  content: '';
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  border: 5px solid transparent;
  border-top-color: #e2e8f0;
}

.timeline-tooltip::before {
  content: '';
  position: absolute;
  top: calc(100% - 1px);
  left: 50%;
  transform: translateX(-50%);
  border: 4px solid transparent;
  border-top-color: #fff;
  z-index: 1;
}

.timeline-state:hover .timeline-tooltip {
  display: flex;
}

.tt-header {
  font-weight: 700;
  font-size: 0.68rem;
  margin-bottom: 2px;
  white-space: nowrap;
}

.tt-state-F { color: #4ade80; }
.tt-state-M { color: #f87171; }
.tt-state-A { color: #fbbf24; }
.tt-state-NH { color: #94a3b8; }
.tt-row {
  white-space: nowrap;
}
.tt-obs {
  max-width: 220px;
  white-space: normal;
  word-break: break-word;
}

.btn-resumen {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  padding: 0.4rem 0.8rem;
  font-size: 0.72rem;
  font-weight: 600;
  color: #fff;
  background: #1E4EA8;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.2s, transform 0.15s;
  margin-left: auto;
  white-space: nowrap;
  flex-shrink: 0;
}

.btn-resumen:hover {
  background: #2563eb;
  transform: translateY(-1px);
}

.btn-resumen svg {
  flex-shrink: 0;
}

/* ===== Resumen Card ===== */
.resumen-card {
  width: 480px;
  max-width: 92vw;
  background: var(--surface, #fff);
  border-radius: 16px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.2), 0 4px 12px rgba(0,0,0,0.06);
  animation: resumenIn 0.25s ease;
  position: relative;
  padding: 1.5rem;
}

@keyframes resumenIn {
  from { opacity: 0; transform: scale(0.95) translateY(12px); }
  to { opacity: 1; transform: scale(1) translateY(0); }
}

.resumen-close {
  position: absolute;
  top: 10px;
  right: 10px;
  z-index: 10;
  background: var(--surface3, #f1f5f9);
  border: none;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--text2, #64748b);
  cursor: pointer;
  transition: background 0.2s, transform 0.15s;
}

.resumen-close:hover {
  background: var(--border, #e2e8f0);
  transform: scale(1.1);
}

.resumen-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.25rem;
}

.resumen-room {
  display: flex;
  align-items: center;
  gap: 10px;
}

.resumen-room-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background: var(--azul, #0D2D6B);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.resumen-room-sub {
  font-size: 0.68rem;
  color: var(--text2, #64748b);
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

.resumen-room-num {
  font-size: 1.4rem;
  font-weight: 800;
  color: var(--text, #1e293b);
  line-height: 1.1;
}

.resumen-chip {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 0.78rem;
  font-weight: 600;
}

.resumen-chip.chip-F { background: #dcfce7; color: #15803d; }
.resumen-chip.chip-M { background: #fecaca; color: #dc2626; }
.resumen-chip.chip-A { background: #fef3c7; color: #b45309; }
.resumen-chip.chip-NH { background: #f1f5f9; color: #64748b; }

.resumen-chip-letter {
  font-weight: 800;
  font-size: 0.9rem;
}

.resumen-mes {
  font-size: 0.78rem;
  font-weight: 600;
  color: var(--text2, #64748b);
  margin-bottom: 1rem;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid var(--border, #e2e8f0);
}

.resumen-chart {
  margin-bottom: 1rem;
}

.resumen-chart-title {
  font-size: 0.65rem;
  font-weight: 600;
  color: var(--text2, #64748b);
  text-transform: uppercase;
  letter-spacing: 0.04em;
  margin-bottom: 8px;
}

.resumen-chart-bars {
  display: flex;
  flex-direction: column;
  gap: 7px;
}

.resumen-chart-row {
  display: flex;
  align-items: center;
  gap: 10px;
}

.resumen-chart-label {
  width: 80px;
  font-size: 0.72rem;
  font-weight: 500;
  color: var(--text, #1e293b);
  flex-shrink: 0;
  text-align: right;
}

.resumen-chart-track {
  flex: 1;
  height: 10px;
  background: var(--surface3, #f1f5f9);
  border-radius: 5px;
  overflow: hidden;
}

.resumen-chart-fill {
  height: 100%;
  border-radius: 5px;
  transition: width 0.5s ease;
  min-width: 2px;
}

.resumen-chart-count {
  width: 70px;
  font-size: 0.72rem;
  font-weight: 600;
  color: var(--text, #1e293b);
  flex-shrink: 0;
}

.resumen-chart-pct {
  font-weight: 400;
  color: var(--text2, #94a3b8);
  font-size: 0.65rem;
}

.resumen-topobs {
  background: var(--surface3, #f8fafc);
  border: 1px solid var(--border, #e2e8f0);
  border-radius: 10px;
  padding: 12px 14px;
  margin-bottom: 0.75rem;
}

.resumen-topobs-text {
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--text, #1e293b);
  line-height: 1.4;
  margin-bottom: 4px;
}

.resumen-topobs-footer {
  display: flex;
  align-items: center;
  gap: 10px;
}

.resumen-topobs-count {
  font-size: 0.68rem;
  font-weight: 600;
  color: var(--azul, #0D2D6B);
  background: rgba(13, 45, 107, 0.08);
  padding: 1px 8px;
  border-radius: 4px;
}

.resumen-topobs-mas {
  font-size: 0.68rem;
  font-weight: 500;
  color: var(--azul, #0D2D6B);
  cursor: pointer;
  text-decoration: underline;
  text-underline-offset: 2px;
}

.resumen-topobs-mas:hover {
  opacity: 0.8;
}

.resumen-obs {
  margin-bottom: 0.5rem;
}

.resumen-obs-title {
  font-size: 0.65rem;
  font-weight: 600;
  color: var(--text2, #64748b);
  text-transform: uppercase;
  letter-spacing: 0.04em;
  margin-bottom: 6px;
}

.resumen-obs-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
}

.resumen-obs-tag {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 4px 10px;
  background: var(--surface3, #f1f5f9);
  border: 1px solid var(--border, #e2e8f0);
  border-radius: 6px;
  font-size: 0.7rem;
  font-weight: 500;
  color: var(--text, #475569);
}

.resumen-obs-count {
  background: var(--border, #e2e8f0);
  border-radius: 4px;
  padding: 0 5px;
  font-size: 0.6rem;
  font-weight: 700;
  color: var(--text2, #64748b);
}

.resumen-nota {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  padding: 10px 12px;
  background: #fffbeb;
  border: 1px solid #fde68a;
  border-radius: 8px;
  font-size: 0.78rem;
  color: #92400e;
  line-height: 1.5;
}

.resumen-nota svg {
  flex-shrink: 0;
  margin-top: 2px;
  color: #d97706;
}

.resumen-empty {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 0.5rem 0;
  color: var(--text2, #94a3b8);
  font-style: italic;
  font-size: 0.8rem;
}

.timeline-day {
  font-size: 0.55rem;
  color: var(--text2);
  font-weight: 600;
  text-align: center;
}

.timeline-legend {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  margin-top: 1rem;
  padding-top: 0.75rem;
  border-top: 1px solid var(--border);
  font-size: 0.7rem;
  color: var(--text2);
}

.timeline-legend span {
  display: flex;
  align-items: center;
  gap: 3px;
}

.legend-dot {
  width: 10px;
  height: 10px;
  border-radius: 3px;
  display: inline-block;
}

.timeline-empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  padding: 2.5rem 1rem;
  color: var(--text2);
  font-size: 0.85rem;
}
</style>

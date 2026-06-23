<template>
  <div>
    <div class="content">
      <div class="hist-wrap">
        <div v-if="!historialFiltrado.length" class="empty-state">
          <div style="font-size:2rem;margin-bottom:0.5rem;">📋</div>
          <p style="color:var(--text2);">Sin cambios registrados todavía</p>
        </div>
        <div v-else>
          <div v-for="(grupo, fecha) in historialAgrupado" :key="fecha" class="carpeta">
            <div class="carpeta-header" @click="toggleCarpeta(fecha)">
              <div :class="['carpeta-icono', carpetasAbiertas.includes(fecha) ? 'abierto' : '']">📁</div>
              <div class="carpeta-info">
                <div class="carpeta-fecha">
                  {{ formatDate(fecha) }}
                  <span v-if="fecha === hoy" class="chip chip-hoy">HOY</span>
                </div>
                <div class="carpeta-meta">
                  <span v-for="(count, estado) in resumenPorEstado(grupo)" :key="estado" :class="['chip', estado]" style="margin-right:0.25rem;">{{ count }} {{ estadoLabel(estado) }}</span>
                </div>
              </div>
              <div class="carpeta-badge">{{ grupo.length }}</div>
              <div class="carpeta-export" @click.stop>
                <button class="btn btn-green" @click="exportarExcel(fecha)" style="font-size:0.72rem;padding:0.25rem 0.55rem;">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                  Excel
                </button>
                <button class="btn btn-primary" @click="exportarPDF(fecha)" style="font-size:0.72rem;padding:0.25rem 0.55rem;">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                  PDF
                </button>
              </div>
            </div>
            <div :class="['carpeta-body', carpetasAbiertas.includes(fecha) ? 'open' : '']">
              <table class="hist-table">
                <thead>
                  <tr>
                    <th>Hora</th>
                    <th>Sección</th>
                    <th>Llamado</th>
                    <th>Cambio</th>
                    <th>Observación</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in grupo" :key="item.id">
                    <td class="td-mono">{{ formatTime(item.ts) }}</td>
                    <td>{{ item.section }}</td>
                    <td>
                      {{ item.room_name }}
                      <div class="td-mono" style="font-size:0.68rem;color:var(--text2);">{{ item.room?.room_id }}</div>
                    </td>
                    <td>
                      <span :class="['chip', item.anterior]" style="margin-right:0.25rem;">{{ estadoLabel(item.anterior) }}</span>
                      <span style="color:var(--text2);margin:0 0.2rem;">→</span>
                      <span :class="['chip', item.nuevo]">{{ estadoLabel(item.nuevo) }}</span>
                    </td>
                    <td style="color:var(--text2);">{{ item.observacion || '—' }}</td>
                    <td style="white-space:nowrap;">
                      <button class="btn-edit" @click="editarItem(item)">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                      </button>
                      <button class="btn-del" @click="confirmarEliminar(item)">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div :class="['modal-overlay', modalEditarOpen ? 'open' : '']" @click.self="cerrarModalEditar">
      <div class="modal">
        <h3>Editar registro</h3>
        <div class="sub">{{ itemEditar?.room_name }} — {{ itemEditar?.section }}</div>
        <div class="estado-grid">
          <div v-for="estado in estadosPosibles" 
            :key="estado"
            :class="['estado-opt', estadoSeleccionado === estado ? 'sel-' + estado : '']"
            @click="estadoSeleccionado = estado">
            {{ estadoIcono(estado) }} {{ estadoLabel(estado) }}
          </div>
        </div>
        <div class="obs-label">Observaciones (opcional)</div>
        <input class="obs-input" v-model="observacionEditar" type="text" placeholder="Ej: Cable roto, en espera de repuesto...">
        <div class="modal-actions">
          <button class="btn" @click="cerrarModalEditar">Cancelar</button>
          <button class="btn btn-primary" @click="guardarEdicion" :disabled="loading">
            <span v-if="loading" class="spinner" style="width:14px;height:14px;border-width:2px;"></span>
            <span v-else>Guardar</span>
          </button>
        </div>
      </div>
    </div>

    <div :class="['confirm-overlay', modalConfirmarOpen ? 'open' : '']" @click.self="cerrarModalConfirmar">
      <div class="confirm-box">
        <h3>Borrar registro</h3>
        <p>Este cambio no se puede deshacer.</p>
        <div class="confirm-actions">
          <button class="btn" @click="cerrarModalConfirmar">Cancelar</button>
          <button class="btn btn-red-solid" id="confirmBtn" @click="eliminarItem" :disabled="loading">
            <span v-if="loading" class="spinner" style="width:14px;height:14px;border-width:2px;"></span>
            <span v-else>Borrar</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useStore } from '../store'
import { api } from '../api'
import * as XLSX from 'xlsx'
import { refreshHistorial } from '../utils.js'

const props = defineProps({
  filtroFecha: { type: String, default: 'all' },
  filtroPiso: { type: String, default: 'all' },
  filtroBusqueda: { type: String, default: '' },
  soloBaños: { type: Boolean, default: false }
})

const store = useStore()

const rooms = computed(() => store.state.rooms)
const historial = computed(() => store.state.historial)

const carpetasAbiertas = ref([])
const modalEditarOpen = ref(false)
const modalConfirmarOpen = ref(false)
const itemEditar = ref(null)
const itemAEliminar = ref(null)
const estadoSeleccionado = ref('funciona')
const observacionEditar = ref('')
const loading = ref(false)

const estadosPosibles = ['funciona', 'no-funciona', 'aislado', 'no-hay']
const hoy = computed(() => {
  const d = new Date()
  return d.toISOString().split('T')[0]
})

const historialFiltrado = computed(() => {
  return historial.value.filter(item => {
    const fechaMatch = props.filtroFecha === 'all' || item.date === props.filtroFecha
    const pisoMatch = props.filtroPiso === 'all' || item.section.startsWith(props.filtroPiso + ' ')
    const busquedaMatch = !props.filtroBusqueda || 
      item.room_name.toLowerCase().includes(props.filtroBusqueda.toLowerCase()) || 
      (item.room?.room_id && item.room.room_id.toLowerCase().includes(props.filtroBusqueda.toLowerCase()))
    const bañosMatch = !props.soloBaños || item.section.includes('Baños')
    return fechaMatch && pisoMatch && busquedaMatch && bañosMatch
  })
})

const historialAgrupado = computed(() => {
  const grupos = {}
  historialFiltrado.value.forEach(item => {
    if (!grupos[item.date]) grupos[item.date] = []
    grupos[item.date].push(item)
  })
  const fechasOrdenadas = Object.keys(grupos).sort((a, b) => b.localeCompare(a))
  const resultado = {}
  fechasOrdenadas.forEach(fecha => {
    resultado[fecha] = grupos[fecha]
  })
  return resultado
})

const fechasUnicas = computed(() => {
  const fechas = new Set(historial.value.map(h => h.date))
  return Array.from(fechas).sort((a, b) => b.localeCompare(a))
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

const formatDate = (fechaStr) => {
  return new Date(fechaStr + 'T12:00:00').toLocaleDateString('es-CO', { 
    weekday: 'long', 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  })
}

const formatTime = (ts) => {
  return new Date(ts).toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' })
}

const resumenPorEstado = (grupo) => {
  const counts = {}
  grupo.forEach(item => {
    counts[item.nuevo] = (counts[item.nuevo] || 0) + 1
  })
  return counts
}

const toggleCarpeta = (fecha) => {
  const index = carpetasAbiertas.value.indexOf(fecha)
  if (index === -1) {
    carpetasAbiertas.value.push(fecha)
  } else {
    carpetasAbiertas.value.splice(index, 1)
  }
}

const editarItem = (item) => {
  itemEditar.value = item
  estadoSeleccionado.value = item.nuevo
  observacionEditar.value = item.observacion || ''
  modalEditarOpen.value = true
}

const cerrarModalEditar = () => {
  modalEditarOpen.value = false
  itemEditar.value = null
  estadoSeleccionado.value = 'funciona'
  observacionEditar.value = ''
}

const guardarEdicion = async () => {
  if (!itemEditar.value) return
  loading.value = true
  try {
    await api.updateHistorial(itemEditar.value.id, { 
      nuevo: estadoSeleccionado.value, 
      observacion: observacionEditar.value 
    })
    await refreshHistorial()
    cerrarModalEditar()
    store.showToast('Edición guardada', 'success')
  } catch (err) {
    store.showToast('Error al guardar edición', 'error')
  } finally {
    loading.value = false
  }
}

const confirmarEliminar = (item) => {
  itemAEliminar.value = item
  modalConfirmarOpen.value = true
}

const cerrarModalConfirmar = () => {
  modalConfirmarOpen.value = false
  itemAEliminar.value = null
}

const eliminarItem = async () => {
  if (!itemAEliminar.value) return
  loading.value = true
  try {
    await api.deleteHistorial(itemAEliminar.value.id)
    await refreshHistorial()
    cerrarModalConfirmar()
    store.showToast('Item eliminado', 'success')
  } catch (err) {
    store.showToast('Error al eliminar item', 'error')
  } finally {
    loading.value = false
  }
}

const exportarExcel = (fecha) => {
  const items = fecha ? historialAgrupado.value[fecha] : historialFiltrado.value
  if (!items || !items.length) return
  const datos = [
    ['HISTÓRICO - CAC Santa Bárbara'],
    ['Generado:', new Date().toLocaleString('es-CO')],
    [],
    ['Fecha', 'Sección', 'Habitación', 'ID', 'Estado Anterior', 'Estado Nuevo', 'Observación', 'Usuario', 'Hora']
  ]
  items.forEach(item => {
    datos.push([
      item.date,
      item.section,
      item.room_name,
      item.room?.room_id || '',
      estadoLabel(item.anterior),
      estadoLabel(item.nuevo),
      item.observacion || '',
      item.user || '',
      item.created_at ? new Date(item.created_at).toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' }) : ''
    ])
  })
  const wb = XLSX.utils.book_new()
  const ws = XLSX.utils.aoa_to_sheet(datos)
  XLSX.utils.book_append_sheet(wb, ws, 'Historial')
  XLSX.writeFile(wb, `Historial${fecha ? '_' + fecha : ''}_${hoy.value}.xlsx`)
}

const exportarPDF = async (fecha) => {
  try {
    const filters = {}
    if (fecha) { filters.date = fecha }
    if (!fecha && props.filtroFecha !== 'all') { filters.date = props.filtroFecha }
    if (!fecha && props.filtroPiso !== 'all') { filters.section = props.filtroPiso }
    if (!fecha && props.filtroBusqueda) { filters.search = props.filtroBusqueda }
    if (!fecha && props.soloBaños) { filters.only_bathrooms = true }
    await api.downloadHistorialPdf(filters)
  } catch (error) {
    store.showToast('Error al descargar PDF', 'error')
  }
}

// Open today's folder by default
if (fechasUnicas.value.includes(hoy.value) && !carpetasAbiertas.value.includes(hoy.value)) {
  carpetasAbiertas.value.push(hoy.value)
}
</script>

<style scoped>
.td-mono {
  font-family: var(--mono);
  font-size: 0.72rem;
  white-space: nowrap;
}

.chip-hoy {
  background: var(--verde-bg) !important;
  color: var(--verde-texto) !important;
  font-size: 0.68rem !important;
  margin-left: 0.5rem;
  padding: 0.15rem 0.45rem !important;
  vertical-align: middle;
}

.btn-del svg,
.btn-edit svg {
  display: block;
}
</style>

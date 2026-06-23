<template>
  <div class="dash-wrap">
    <div class="dash-label">Panel de Control</div>
    <div class="dash">
      <div class="dash-card ronda" @click="goToRonda">
      <div class="dash-row">
        <div class="dash-icon">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <h3>Ronda Diaria</h3>
        <span :class="['chip', ronda?.completada ? 'chip-verde' : 'chip-ambar']" style="margin-left:auto;font-size:0.72rem;">
          {{ ronda?.completada ? 'Completada' : 'En progreso' }}
        </span>
      </div>

      <div class="ronda-body">
        <div class="ronda-ring-wrap">
          <svg class="ronda-ring" viewBox="0 0 36 36">
            <path class="ronda-ring-bg" d="M18 2.0845a15.9155 15.9155 0 0 1 0 31.831a15.9155 15.9155 0 0 1 0-31.831" />
            <path class="ronda-ring-fill" d="M18 2.0845a15.9155 15.9155 0 0 1 0 31.831a15.9155 15.9155 0 0 1 0-31.831"
              :stroke-dasharray="stats.porcentajeRevisados + ', 100'" />
            <text class="ronda-ring-pct" x="18" y="20.5">{{ stats.porcentajeRevisados }}%</text>
            <text class="ronda-ring-lbl" x="18" y="26">revisado</text>
          </svg>
        </div>

        <div class="ronda-stats">
          <div class="ronda-stat">
            <span class="ronda-dot verde"></span>
            <span class="ronda-n">{{ stats.funciona }}</span>
            <span class="ronda-l">Funcionan</span>
          </div>
          <div class="ronda-stat">
            <span class="ronda-dot rojo"></span>
            <span class="ronda-n">{{ stats.noFunciona }}</span>
            <span class="ronda-l">No Funcionan</span>
          </div>
          <div class="ronda-stat">
            <span class="ronda-dot ambar"></span>
            <span class="ronda-n">{{ stats.aislado }}</span>
            <span class="ronda-l">Aislados</span>
          </div>
          <div class="ronda-stat">
            <span class="ronda-dot gris"></span>
            <span class="ronda-n">{{ stats.noHay }}</span>
            <span class="ronda-l">Sin Llamado</span>
          </div>
        </div>
      </div>

      <div class="ronda-stats-row">
        <div class="ronda-counter">
          <span class="rc-num">{{ stats.revisados }}</span>
          <span class="rc-bar">/</span>
          <span class="rc-total">{{ stats.total }}</span>
          <span class="rc-lbl">habitaciones</span>
        </div>
        <div class="ronda-badge" :style="{ background: badgeBg }">
          <span class="rb-pct">{{ stats.porcentajeRevisados }}%</span>
          <span class="rb-lbl">eficiencia</span>
        </div>
      </div>

      <div class="ronda-footer">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        <span>{{ fechaActual }}</span>
        <span class="ronda-footer-divider"></span>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <span>Últ: {{ ultimaActualizacion }}</span>
      </div>

      <div class="dash-arrow">
        <span>Ir a ronda diaria</span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </div>
    </div>

    <div class="dash-right">
      <div class="dash-separator" aria-hidden="true"></div>

      <div class="dash-card historial compact" @click="goToHistorial">
        <div class="dash-row">
          <div class="dash-icon" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#1d4ed8">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </div>
          <h3>Histórico</h3>
          <span class="chip" style="margin-left:auto;background:#dbeafe;color:#1d4ed8;font-size:0.72rem;position:relative;">
            <span class="chip-count">{{ historial.length }}</span> registros
          </span>
        </div>
        <div class="dash-grid-3">
          <div>
            <div class="dg-n" style="color:#2563eb">{{ diasUnicos }}</div>
            <div class="dg-l">
              <svg class="dg-icon" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              Días
            </div>
          </div>
          <div>
            <div class="dg-n" style="color:#7c3aed">{{ historial.length }}</div>
            <div class="dg-l">
              <svg class="dg-icon" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
              Cambios
            </div>
          </div>
          <div>
            <div class="dg-n" style="color:#0891b2">{{ habitacionesTotal > 0 ? Math.round(historial.length / Math.max(diasUnicos, 1)) : 0 }}</div>
            <div class="dg-l">
              <svg class="dg-icon" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
              Cambios/día
            </div>
          </div>
        </div>
        <div class="dash-arrow">
          <span>Ir al histórico</span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </div>
      </div>

      <div class="dash-card analisis compact" @click="goToAnalisis">
        <div class="dash-row">
          <div class="dash-icon" style="background:linear-gradient(135deg,#ede9fe,#ddd6fe);color:#6d28d9">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 21l-4.35-4.35M11 4a7 7 0 1 0 0 14 7 7 0 0 0 0-14z"/><path d="M16 5l3 3"/><path d="M19 2l3 3"/></svg>
          </div>
          <h3>Comportamiento</h3>
          <span class="chip" style="margin-left:auto;background:#ede9fe;color:#6d28d9;font-size:0.72rem;position:relative;">
            <span class="chip-count">{{ habitacionesUnicas }}</span> hab.
          </span>
        </div>
        <div class="dash-grid-2">
          <div>
            <div class="dg-n" style="color:#dc2626">{{ incidenciasCount }}</div>
            <div class="dg-l">
              <svg class="dg-icon" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              Incidencias
            </div>
            <div class="dg-bar"><div class="dg-bar-fill dg-bar-rojo" :style="{ width: pctIncidencias + '%' }"></div></div>
          </div>
          <div>
            <div class="dg-n" style="color:#6d28d9">{{ habitacionesUnicas }}</div>
            <div class="dg-l">
              <svg class="dg-icon" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
              Habitaciones
            </div>
            <div class="dg-bar"><div class="dg-bar-fill dg-bar-violeta" :style="{ width: pctHabitaciones + '%' }"></div></div>
          </div>
        </div>
        <div class="dash-arrow">
          <span>Ver comportamiento</span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script setup>
import { computed } from 'vue'
import { useStore } from '../store'

const store = useStore()

const ronda = computed(() => store.state.ronda)
const rooms = computed(() => store.state.rooms)
const historial = computed(() => store.state.historial)

const stats = computed(() => {
  const counts = { funciona: 0, noFunciona: 0, aislado: 0, noHay: 0 }
  let revisados = 0
  rooms.value.forEach(room => {
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
    total: rooms.value.length,
    revisados,
    porcentajeRevisados: rooms.value.length ? Math.round((revisados / rooms.value.length) * 100) : 0
  }
})

const habitacionesTotal = computed(() => rooms.value.length)

const diasUnicos = computed(() => {
  const dias = new Set()
  historial.value.forEach(h => dias.add(h.date))
  return dias.size
})

const habitacionesUnicas = computed(() => {
  const habs = new Set()
  historial.value.forEach(h => habs.add(h.room_name))
  return habs.size
})

const incidenciasCount = computed(() => {
  return historial.value.filter(h => h.nuevo === 'no-funciona').length
})

const totalCount = computed(() => historial.value.length)

const pctIncidencias = computed(() => {
  return totalCount.value > 0 ? Math.round((incidenciasCount.value / totalCount.value) * 100) : 0
})

const pctHabitaciones = computed(() => {
  return totalCount.value > 0 ? Math.round((habitacionesUnicas.value / totalCount.value) * 100) : 0
})

const ultimasRooms = computed(() => {
  return rooms.value.filter(r => r.timestamp).sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp)).slice(0, 3)
})

const ultimaActualizacion = computed(() => {
  if (!ultimasRooms.value.length) return '—'
  const d = new Date(ultimasRooms.value[0].timestamp)
  return d.toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' })
})

const fechaActual = computed(() => {
  return new Date().toLocaleDateString('es-CO', { day: 'numeric', month: 'short', year: 'numeric' })
})

const badgeBg = computed(() => {
  const p = stats.value.porcentajeRevisados
  if (p >= 80) return 'linear-gradient(135deg, #15803d, #22c55e)'
  if (p >= 50) return 'linear-gradient(135deg, #b45309, #f59e0b)'
  return 'linear-gradient(135deg, #b91c1c, #ef4444)'
})

const goToRonda = () => store.setCurrentView('ronda')
const goToHistorial = () => store.setCurrentView('historial')
const goToAnalisis = () => store.setCurrentView('analisis')
</script>

<style scoped>
.dash {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  padding: 1rem;
  max-width: 960px;
  margin: 0 auto;
  flex: 1;
  box-sizing: border-box;
  align-content: center;
  overflow: hidden;
}

.dash-wrap {
  display: flex;
  flex: 1;
  max-width: 1000px;
  margin: 0 auto;
  width: 100%;
  box-sizing: border-box;
}

.dash-label {
  writing-mode: vertical-lr;
  font-size: 0.7rem;
  font-weight: 700;
  color: var(--text2);
  text-transform: uppercase;
  letter-spacing: 2px;
  opacity: 0.4;
  padding: 0.5rem 0.4rem;
  user-select: none;
  flex-shrink: 0;
  align-self: center;
}

@keyframes dashEntrance {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.dash-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 1.75rem 1.75rem;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  box-shadow: var(--shadow-sm);
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.dash-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-lg);
}

.dash-card:active {
  transform: translateY(-2px);
}

.dash-card::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.dash-card:hover::after {
  opacity: 1;
}

.dash-card.ronda::after {
  background: linear-gradient(90deg, var(--verde), #4ade80);
}

.dash-card.historial::after {
  background: linear-gradient(90deg, #3b82f6, #60a5fa);
}

.dash-card.ronda:hover {
  border-color: rgba(34, 197, 94, 0.3);
  background: linear-gradient(135deg, #f0fdf4, #fff);
  box-shadow: 0 8px 32px rgba(34, 197, 94, 0.15);
}

.dash-card.historial:hover {
  border-color: rgba(59, 130, 246, 0.3);
  background: linear-gradient(135deg, #eff6ff, #fff);
  box-shadow: 0 8px 32px rgba(59, 130, 246, 0.15);
}

.dash-right {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  position: relative;
}

.dash-separator {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  width: 1px;
  height: 20%;
  background: linear-gradient(180deg, transparent, var(--border2), transparent);
  z-index: 1;
  pointer-events: none;
  opacity: 0.4;
}

.dash-card.compact {
  padding: 1.5rem 1.75rem;
  gap: 0.85rem;
  flex: 1;
  justify-content: center;
  animation: cardSlide 0.4s ease backwards;
}

.dash-card.historial.compact {
  animation-delay: 0.1s;
}

.dash-card.analisis.compact {
  animation-delay: 0.2s;
}

@keyframes cardSlide {
  from { opacity: 0; transform: translateY(12px); }
  to { opacity: 1; transform: translateY(0); }
}

.dash-card.analisis:hover {
  border-color: rgba(139, 92, 246, 0.3);
  background: linear-gradient(135deg, #f5f3ff, #fff);
  box-shadow: 0 8px 32px rgba(139, 92, 246, 0.15);
}

.dash-row {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  width: 100%;
}

.dash-card.compact .dash-icon {
  width: 54px;
  height: 54px;
  margin-bottom: 0;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.dash-card.compact .dash-icon svg {
  width: 24px;
  height: 24px;
}

.dash-card.compact:hover .dash-icon {
  transform: scale(1.08);
  box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.dash-card.compact h3 {
  font-size: 1.15rem;
  margin-bottom: 0;
}

.dash-grid-3,
.dash-grid-2 {
  display: grid;
  gap: 0.4rem;
  background: var(--surface3);
  border-radius: var(--radius-md);
  padding: 1rem 1.15rem;
  border: 1px solid var(--border);
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.dash-card.compact:hover .dash-grid-3,
.dash-card.compact:hover .dash-grid-2 {
  border-color: var(--border2);
  box-shadow: inset 0 1px 4px rgba(0,0,0,0.03);
}

.dash-grid-3 { grid-template-columns: repeat(3, 1fr); }
.dash-grid-2 { grid-template-columns: repeat(2, 1fr); }

.dash-grid-3 > div,
.dash-grid-2 > div {
  text-align: center;
}

.dg-n {
  font-size: 1.4rem;
  font-weight: 800;
  line-height: 1;
  color: var(--text);
}

.dg-l {
  font-size: 0.65rem;
  color: var(--text2);
  font-weight: 600;
  margin-top: 0.25rem;
  text-transform: uppercase;
  letter-spacing: 0.3px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.3rem;
}

.dg-icon {
  flex-shrink: 0;
  opacity: 0.5;
}

.dg-bar {
  height: 3px;
  background: var(--border);
  border-radius: 2px;
  margin: 0.4rem 0.5rem 0;
  overflow: hidden;
}

.dg-bar-fill {
  height: 100%;
  border-radius: 2px;
  transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.dg-bar-rojo { background: linear-gradient(90deg, #f87171, #ef4444); }
.dg-bar-violeta { background: linear-gradient(90deg, #a78bfa, #8b5cf6); }
.dg-bar-verde { background: linear-gradient(90deg, #4ade80, #22c55e); }
.dg-bar-ambar { background: linear-gradient(90deg, #fbbf24, #f59e0b); }
.dg-bar-gris { background: linear-gradient(90deg, #9ca3af, #6b7280); }

.dash-card.analisis::after {
  background: linear-gradient(90deg, #8b5cf6, #a78bfa);
}

.dash-card.analisis .dash-icon {
  background: linear-gradient(135deg, #ede9fe, #ddd6fe);
  color: #6d28d9;
}

.dash-icon {
  width: 48px;
  height: 48px;
  border-radius: var(--radius-md);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  flex-shrink: 0;
}

.dash-card.ronda {
  animation: cardSlide 0.4s ease backwards;
  gap: 0.85rem;
}

.dash-card.ronda:hover {
  border-color: rgba(34, 197, 94, 0.3);
  background: linear-gradient(135deg, #f0fdf4, #fff);
  box-shadow: 0 8px 32px rgba(34, 197, 94, 0.12);
}

.dash-card.ronda .dash-icon {
  width: 54px;
  height: 54px;
  background: linear-gradient(135deg, #dcfce7, #bbf7d0);
  color: #15803d;
}

.dash-card.ronda .dash-icon svg {
  width: 24px;
  height: 24px;
}

.dash-card.ronda:hover .dash-icon {
  transform: scale(1.08);
  box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.dash-card.ronda::after {
  background: linear-gradient(90deg, #22c55e, #4ade80);
}

.dash-card.ronda .dash-arrow {
  opacity: 0;
  transform: translateX(-4px);
  transition: all 0.2s ease;
}

.dash-card.ronda:hover .dash-arrow {
  opacity: 1;
  transform: translateX(0);
  color: #16a34a;
}

.dash-card.ronda:hover .dash-arrow svg {
  transform: translateX(3px);
}

.dash-card.historial .dash-icon {
  background: linear-gradient(135deg, #dbeafe, #bfdbfe);
  color: #1d4ed8;
}

.dash-card h3 {
  font-size: 1.15rem;
  font-weight: 700;
  margin-bottom: 0;
}

.dash-desc {
  font-size: 0.82rem;
  color: var(--text2);
  line-height: 1.5;
}

.ronda-body {
  display: flex;
  align-items: center;
  gap: 1.25rem;
}

.ronda-ring-wrap {
  flex-shrink: 0;
}

.ronda-ring {
  width: 100px;
  height: 100px;
}

.ronda-ring-bg {
  fill: none;
  stroke: var(--border);
  stroke-width: 2.5;
}

.ronda-ring-fill {
  fill: none;
  stroke: #22c55e;
  stroke-width: 2.5;
  stroke-linecap: round;
  transition: stroke-dasharray 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.ronda-ring-pct {
  font-size: 7.5px;
  font-weight: 800;
  text-anchor: middle;
  fill: var(--text);
}

.ronda-ring-lbl {
  font-size: 3.2px;
  text-anchor: middle;
  fill: var(--text2);
  font-weight: 500;
}

.ronda-stats {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.35rem 0.75rem;
  flex: 1;
}

.ronda-stat {
  display: flex;
  align-items: center;
  gap: 0.4rem;
}

.ronda-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  flex-shrink: 0;
}

.ronda-dot.verde { background: #22c55e; }
.ronda-dot.rojo { background: #ef4444; }
.ronda-dot.ambar { background: #f59e0b; }
.ronda-dot.gris { background: #9ca3af; }

.ronda-n {
  font-size: 1.15rem;
  font-weight: 800;
  line-height: 1;
  color: var(--text);
  min-width: 1.2rem;
  text-align: right;
}

.ronda-l {
  font-size: 0.62rem;
  color: var(--text2);
  font-weight: 500;
}

.ronda-stats-row {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.ronda-counter {
  display: flex;
  align-items: baseline;
  gap: 0.15rem;
  background: var(--surface3);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  padding: 0.4rem 0.75rem;
  flex: 1;
}

.rc-num {
  font-size: 1.4rem;
  font-weight: 800;
  color: var(--text);
  line-height: 1;
}

.rc-bar {
  font-size: 1.1rem;
  font-weight: 300;
  color: var(--text2);
}

.rc-total {
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--text2);
}

.rc-lbl {
  font-size: 0.6rem;
  color: var(--text2);
  font-weight: 500;
  margin-left: auto;
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

.ronda-badge {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  border-radius: var(--radius-sm);
  padding: 0.35rem 0.85rem;
  min-width: 70px;
}

.rb-pct {
  font-size: 1.2rem;
  font-weight: 800;
  color: #fff;
  line-height: 1.1;
}

.rb-lbl {
  font-size: 0.55rem;
  color: rgba(255,255,255,0.8);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.3px;
  margin-top: 0.05rem;
}

.ronda-rooms {
  display: flex;
  gap: 0.4rem;
  flex-wrap: wrap;
}

.ronda-room {
  display: flex;
  align-items: center;
  gap: 0.3rem;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 6px;
  padding: 0.15rem 0.45rem;
  font-size: 0.7rem;
}

.rr-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  flex-shrink: 0;
}

.rr-dot.funciona { background: #22c55e; }
.rr-dot.no-funciona,
.rr-dot.rojo { background: #ef4444; }
.rr-dot.aislado { background: #f59e0b; }
.rr-dot.no-hay,
.rr-dot.gris { background: #9ca3af; }

.rr-name {
  font-weight: 500;
  color: var(--text);
}

.ronda-room .chip {
  font-size: 0.6rem;
  padding: 0.05rem 0.35rem;
}

.ronda-footer-divider {
  width: 1px;
  height: 12px;
  background: var(--border2);
  opacity: 0.5;
}

.ronda-footer {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.75rem;
  color: var(--text2);
  font-weight: 500;
  padding: 0.4rem 0.75rem;
  background: var(--surface3);
  border-radius: var(--radius-sm);
  border: 1px solid var(--border);
}

.ronda-footer svg {
  flex-shrink: 0;
  color: var(--verde);
  opacity: 0.7;
}

.dash-arrow {
  display: flex;
  align-items: center;
  gap: 0.35rem;
  font-size: 0.72rem;
  color: var(--text2);
  font-weight: 500;
  transition: all 0.2s ease;
  opacity: 0;
  transform: translateX(-4px);
}

.dash-card.compact:hover .dash-arrow {
  opacity: 1;
  transform: translateX(0);
  color: var(--azul);
}

.dash-arrow svg {
  transition: transform 0.2s ease;
}

.dash-card.compact:hover .dash-arrow svg {
  transform: translateX(3px);
}

.chip {
  display: inline-flex;
  border-radius: 5px;
  padding: 0.15rem 0.5rem;
  font-size: 0.68rem;
  font-weight: 600;
  flex-shrink: 0;
}

.chip.funciona { background: var(--verde-bg); color: var(--verde-texto); }
.chip.no-funciona { background: var(--rojo-bg); color: var(--rojo-texto); }
.chip.aislado { background: var(--amarillo-bg); color: var(--amarillo-texto); }
.chip.no-hay { background: var(--gris-bg); color: var(--gris-texto); }
.chip-verde { background: #dcfce7; color: #15803d; }
.chip-ambar { background: #fef3c7; color: #b45309; }

.chip-count {
  display: inline-block;
  animation: countPop 0.4s ease backwards;
}

.dash-card.historial .chip-count { animation-delay: 0.3s; }
.dash-card.analisis .chip-count { animation-delay: 0.4s; }

@keyframes countPop {
  0% { transform: scale(0); opacity: 0; }
  60% { transform: scale(1.2); }
  100% { transform: scale(1); opacity: 1; }
}

@media (max-width: 700px) {
  .dash-label { display: none; }
  .dash-wrap { display: block; }
  .dash { grid-template-columns: 1fr; padding: 1rem; height: auto; overflow: auto; }
  .ronda-body { flex-direction: column; gap: 0.75rem; }
  .ronda-ring { width: 72px; height: 72px; }
  .dash-separator { display: none; }
}
</style>

<template>
  <div class="dash-content">
    <div class="welcome">
      <h1 class="welcome-title">Bienvenido</h1>
      <p class="welcome-sub">Sistema de Rondas Diarias</p>
    </div>

    <div class="kpi-grid">
      <div class="kpi-card card-azul" style="animation-delay:0s">
        <div class="kpi-bg-circle"></div>
        <div class="kpi-glow"></div>
        <div class="kpi-shine"></div>
        <div class="kpi-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <div class="kpi-number"><span class="num-anim">{{ stats.total }}</span></div>
        <div class="kpi-label">Habitaciones</div>
        <div class="kpi-sub">totales registradas</div>
        <svg class="kpi-spark" viewBox="0 0 60 24" fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="1.5">
          <polyline points="0,16 12,10 24,18 36,6 48,14 60,8" />
        </svg>
      </div>
      <div class="kpi-card card-teal" style="animation-delay:0.06s">
        <div class="kpi-bg-circle"></div>
        <div class="kpi-glow"></div>
        <div class="kpi-shine"></div>
        <div class="kpi-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
        </div>
        <div class="kpi-number"><span class="num-anim">{{ stats.llamados }}</span></div>
        <div class="kpi-label">Llamados</div>
        <div class="kpi-sub">dispositivos de llamado</div>
        <svg class="kpi-spark" viewBox="0 0 60 24" fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="1.5">
          <polyline points="0,18 10,8 20,14 30,4 40,12 50,6 60,10" />
        </svg>
      </div>
      <div class="kpi-card card-verde" style="animation-delay:0.12s">
        <div class="kpi-bg-circle"></div>
        <div class="kpi-glow"></div>
        <div class="kpi-shine"></div>
        <div class="kpi-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8h16"/><path d="M6 12V8"/><path d="M10 12V8"/><path d="M14 12V8"/><path d="M18 12V8"/><path d="M4 16h16"/><path d="M2 20h20"/></svg>
        </div>
        <div class="kpi-number"><span class="num-anim">{{ stats.baños }}</span></div>
        <div class="kpi-label">Baños</div>
        <div class="kpi-sub">dispositivos en baños</div>
        <svg class="kpi-spark" viewBox="0 0 60 24" fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="1.5">
          <polyline points="0,8 12,12 24,6 36,16 48,10 60,14" />
        </svg>
      </div>
      <div class="kpi-card no-icon card-oscuro" style="animation-delay:0.18s">
        <div class="kpi-bg-circle"></div>
        <div class="kpi-glow"></div>
        <div class="kpi-shine"></div>
        <div class="kpi-number"><span class="num-anim">{{ stats.revisados }}</span><span class="kpi-total">/{{ stats.total }}</span></div>
        <div class="kpi-label">Revisados hoy</div>
        <div class="kpi-sub">{{ stats.porcentajeRevisados }}% completado</div>
        <div class="kpi-progress">
          <div class="kpi-progress-bar" :style="{ width: stats.porcentajeRevisados + '%' }"></div>
        </div>
        <svg class="kpi-spark" viewBox="0 0 60 24" fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="1.5">
          <polyline points="0,12 12,8 24,4 36,2 48,4 60,2" />
        </svg>
      </div>
    </div>

    <div class="kpi-grid">
      <div class="kpi-card no-icon card-celeste" style="animation-delay:0.3s">
        <div class="kpi-bg-circle"></div>
        <div class="kpi-glow"></div>
        <div class="kpi-shine"></div>
        <div class="kpi-number"><span class="num-anim">{{ stats.funciona }}</span></div>
        <div class="kpi-label">Funcionan</div>
        <div class="kpi-sub">dispositivos operativos</div>
        <svg class="kpi-spark" viewBox="0 0 60 24" fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="1.5">
          <polyline points="0,16 12,6 24,12 36,4 48,10 60,4" />
        </svg>
      </div>
      <div class="kpi-card no-icon card-ambar" style="animation-delay:0.3s">
        <div class="kpi-bg-circle"></div>
        <div class="kpi-glow"></div>
        <div class="kpi-shine"></div>
        <div class="kpi-number"><span class="num-anim">{{ stats.noFunciona }}</span></div>
        <div class="kpi-label">No funcionan</div>
        <div class="kpi-sub">dispositivos con falla</div>
        <svg class="kpi-spark" viewBox="0 0 60 24" fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="1.5">
          <polyline points="0,8 12,18 24,12 36,20 48,14 60,18" />
        </svg>
      </div>
      <div class="kpi-card no-icon card-purpura" style="animation-delay:0.36s">
        <div class="kpi-bg-circle"></div>
        <div class="kpi-glow"></div>
        <div class="kpi-shine"></div>
        <div class="kpi-number"><span class="num-anim">{{ stats.noHay }}</span></div>
        <div class="kpi-label">No hay</div>
        <div class="kpi-sub">dispositivos faltantes</div>
        <svg class="kpi-spark" viewBox="0 0 60 24" fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="1.5">
          <polyline points="0,14 12,20 24,16 36,22 48,18 60,20" />
        </svg>
      </div>
      <div class="kpi-card card-rojo" style="animation-delay:0.42s">
        <div class="kpi-bg-circle"></div>
        <div class="kpi-glow"></div>
        <div class="kpi-shine"></div>
        <div class="kpi-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div class="kpi-number"><span class="num-anim">{{ stats.aislado }}</span></div>
        <div class="kpi-label">En aislamiento</div>
        <div class="kpi-sub">habitaciones aisladas</div>
        <svg class="kpi-spark" viewBox="0 0 60 24" fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="1.5">
          <polyline points="0,12 12,18 24,8 36,14 48,4 60,10" />
        </svg>
      </div>
    </div>


    <div class="quick-grid">
      <div class="quick-card" style="animation-delay:0.48s" @click="irA('ronda')">
        <div class="quick-card-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <div>
          <h4>Ronda de Hoy</h4>
          <p>Revisar estado de llamados y baños</p>
        </div>
        <div class="quick-arrow">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </div>
      </div>
      <div class="quick-card" style="animation-delay:0.54s" @click="irA('historial')">
        <div class="quick-card-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div>
          <h4>Histórico</h4>
          <p>Cambios y registro histórico de rondas</p>
        </div>
        <div class="quick-arrow">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </div>
      </div>
      <div class="quick-card" style="animation-delay:0.6s" @click="irA('configuracion')">
        <div class="quick-card-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        </div>
        <div>
          <h4>Configuración</h4>
          <p>Administrar habitaciones y estadísticas</p>
        </div>
        <div class="quick-arrow">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useStore } from '../store'

const store = useStore()

const rooms = computed(() => store.state.rooms)
const stats = computed(() => {
  const counts = { total: 0, llamados: 0, baños: 0, funciona: 0, noFunciona: 0, aislado: 0, noHay: 0, revisados: 0 }
  rooms.value.forEach(r => {
    counts.total++
    if (r.type === 'llamados') counts.llamados++
    else counts.baños++
    if (r.timestamp) counts.revisados++
    switch (r.estado) {
      case 'funciona': counts.funciona++; break
      case 'no-funciona': counts.noFunciona++; break
      case 'aislado': counts.aislado++; break
      case 'no-hay': counts.noHay++; break
    }
  })
  counts.porcentajeRevisados = counts.total ? Math.round((counts.revisados / counts.total) * 100) : 0
  return counts
})

const irA = (view) => {
  store.setCurrentView(view)
}
</script>

<style scoped>
.dash-content {
  padding: 22px 28px;
  overflow-y: auto;
  position: relative;
  min-height: 100%;
  display: flex;
  flex-direction: column;
}
.dash-content::before {
  content: '';
  position: fixed;
  inset: 0;
  opacity: 0.015;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  pointer-events: none;
  z-index: 0;
}
.welcome-title {
  font-size: 26px;
  font-weight: 800;
  color: #0f2744;
  margin-bottom: 2px;
  animation: fadeDown 0.4s ease backwards;
}
.welcome-sub {
  font-size: 13.5px;
  color: #64748b;
  margin-bottom: 18px;
  animation: fadeDown 0.4s 0.1s ease backwards;
}

@keyframes fadeDown {
  from { opacity: 0; transform: translateY(-8px); }
  to { opacity: 1; transform: translateY(0); }
}

.kpi-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 14px;
  margin-bottom: 14px;
}
.kpi-card {
  border-radius: 16px;
  padding: 16px 20px;
  position: relative;
  overflow: hidden;
  color: #fff;
  min-height: 105px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  cursor: default;
  animation: cardIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) backwards;
}
@keyframes cardIn {
  from { opacity: 0; transform: translateY(24px) scale(0.95); }
  to { opacity: 1; transform: translateY(0) scale(1); }
}
.kpi-card:hover {
  transform: translateY(-6px) scale(1.01);
  box-shadow: 0 16px 48px rgba(0,0,0,0.18);
}
.kpi-card:active {
  transform: translateY(-2px) scale(0.99);
}
.kpi-card .kpi-glow {
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle at 30% 40%, rgba(255,255,255,0.08) 0%, transparent 60%);
  pointer-events: none;
  transition: opacity 0.4s ease;
  opacity: 0;
}
.kpi-card:hover .kpi-glow {
  opacity: 1;
}
.kpi-card .kpi-shine {
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.03) 50%, transparent 70%);
  pointer-events: none;
  transition: transform 0.6s ease;
  transform: translateX(-100%) rotate(25deg);
}
.kpi-card:hover .kpi-shine {
  transform: translateX(100%) rotate(25deg);
}
.kpi-card .kpi-bg-circle {
  position: absolute;
  bottom: -30px;
  right: -30px;
  width: 160px;
  height: 160px;
  border-radius: 50%;
  background: rgba(255,255,255,0.05);
  pointer-events: none;
  transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.kpi-card:hover .kpi-bg-circle {
  transform: scale(1.3);
  opacity: 0.15;
}
.kpi-card::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, rgba(255,255,255,0.3), rgba(255,255,255,0.05));
}
.kpi-card .kpi-icon {
  position: absolute;
  top: 12px;
  right: 14px;
  opacity: 0.2;
  transition: all 0.4s ease;
}
.kpi-card:hover .kpi-icon {
  opacity: 0.4;
  transform: scale(1.1) rotate(-5deg);
}
.kpi-card .kpi-icon svg {
  width: 24px;
  height: 24px;
}
.kpi-card .kpi-number {
  font-size: 40px;
  font-weight: 800;
  line-height: 1;
  margin-bottom: 5px;
  letter-spacing: -1px;
  position: relative;
  z-index: 1;
}
.kpi-card .kpi-total {
  font-size: 14px;
  font-weight: 600;
  opacity: 0.5;
  margin-left: 2px;
}
.num-anim {
  display: inline-block;
  animation: numPop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) backwards;
}
@keyframes numPop {
  0% { opacity: 0; transform: scale(0.5) translateY(8px); }
  60% { transform: scale(1.08) translateY(-2px); }
  100% { opacity: 1; transform: scale(1) translateY(0); }
}
.kpi-card:nth-child(1) .num-anim { animation-delay: 0.15s; }
.kpi-card:nth-child(2) .num-anim { animation-delay: 0.25s; }
.kpi-card:nth-child(3) .num-anim { animation-delay: 0.35s; }
.kpi-card:nth-child(4) .num-anim { animation-delay: 0.45s; }
.kpi-card:nth-child(5) .num-anim { animation-delay: 0.55s; }
.kpi-card:nth-child(6) .num-anim { animation-delay: 0.65s; }
.kpi-card:nth-child(7) .num-anim { animation-delay: 0.75s; }
.kpi-card:nth-child(8) .num-anim { animation-delay: 0.85s; }

.kpi-card .kpi-label {
  font-size: 11px;
  font-weight: 700;
  opacity: 0.95;
  letter-spacing: 0.5px;
  text-transform: uppercase;
  position: relative;
  z-index: 1;
}
.kpi-card .kpi-sub {
  font-size: 10.5px;
  opacity: 0.65;
  margin-top: 2px;
  font-weight: 400;
  position: relative;
  z-index: 1;
}
.kpi-card.no-icon {
  padding-bottom: 16px;
}
.kpi-card.no-icon .kpi-number {
  font-size: 36px;
}

.kpi-card .kpi-spark {
  position: absolute;
  bottom: 10px;
  right: 14px;
  width: 52px;
  height: 18px;
  opacity: 0.4;
  pointer-events: none;
  transition: opacity 0.3s ease;
}
.kpi-card:hover .kpi-spark {
  opacity: 0.7;
}

.kpi-progress {
  position: relative;
  z-index: 1;
  width: 100%;
  height: 4px;
  background: rgba(255,255,255,0.15);
  border-radius: 4px;
  margin-top: 6px;
  overflow: hidden;
}
.kpi-progress-bar {
  height: 100%;
  border-radius: 4px;
  background: rgba(255,255,255,0.7);
  transition: width 1s cubic-bezier(0.34, 1.56, 0.64, 1) 0.4s;
}

.quick-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 14px;
  margin-top: 6px;
}
.quick-card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  padding: 14px 16px;
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  position: relative;
  overflow: hidden;
  animation: cardIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) backwards;
}
.quick-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, #3b82f6, #6366f1);
  opacity: 0;
  transition: opacity 0.3s ease;
}
.quick-card:hover::before {
  opacity: 1;
}
.quick-card:hover {
  border-color: #b9c8e0;
  box-shadow: 0 8px 24px rgba(0,0,0,0.1);
  transform: translateY(-3px);
}
.quick-card:active {
  transform: translateY(0) scale(0.98);
}
.quick-card-icon {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  background: #eff4ff;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: all 0.3s ease;
}
.quick-card:hover .quick-card-icon {
  background: #dbeafe;
  transform: scale(1.08);
}
.quick-card-icon svg {
  width: 20px;
  height: 20px;
  color: #1d4ed8;
  transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.quick-card:hover .quick-card-icon svg {
  transform: rotate(-8deg) scale(1.1);
}
.quick-card h4 {
  font-size: 13px;
  font-weight: 700;
  color: #0f2744;
  margin-bottom: 1px;
}
.quick-card p {
  font-size: 11px;
  color: #64748b;
  line-height: 1.3;
}
.quick-arrow {
  margin-left: auto;
  color: #cbd5e1;
  transition: all 0.3s ease;
  flex-shrink: 0;
}
.quick-card:hover .quick-arrow {
  color: #4f46e5;
  transform: translateX(4px);
}

.card-azul { background: linear-gradient(135deg, #1e3a5f, #2a4a7a); }
.card-teal { background: linear-gradient(135deg, #0d9488, #14b8a6); }
.card-verde { background: linear-gradient(135deg, #166534, #22c55e); }
.card-rojo { background: linear-gradient(135deg, #b91c1c, #ef4444); }
.card-celeste { background: linear-gradient(135deg, #0369a1, #0ea5e9); }
.card-ambar { background: linear-gradient(135deg, #d97706, #f59e0b); }
.card-purpura { background: linear-gradient(135deg, #6b21a8, #a855f7); }
.card-oscuro { background: linear-gradient(135deg, #0f766e, #14b8a6); }

</style>

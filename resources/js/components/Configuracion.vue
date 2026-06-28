<template>
  <div class="cf-wrap">
    <div class="cf-hero">
      <div class="cf-hero-icon">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      </div>
      <div>
        <h2 class="cf-hero-title">Configuración</h2>
        <p class="cf-hero-desc">Administrá usuarios, sedes, servicios y llamados</p>
      </div>
    </div>

    <div class="cf-tabs">
      <button :class="['cf-tab', tab === 'usuarios' ? 'active' : '']" @click="tab = 'usuarios'">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Usuarios
      </button>
      <button :class="['cf-tab', tab === 'sedes' ? 'active' : '']" @click="tab = 'sedes'">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        Sedes
      </button>
      <button :class="['cf-tab', tab === 'servicios' ? 'active' : '']" @click="tab = 'servicios'">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><line x1="9" y1="6" x2="10" y2="6"/><line x1="14" y1="6" x2="15" y2="6"/><line x1="9" y1="10" x2="10" y2="10"/><line x1="14" y1="10" x2="15" y2="10"/><line x1="9" y1="14" x2="10" y2="14"/><line x1="14" y1="14" x2="15" y2="14"/></svg>
        Servicios
      </button>
      <button :class="['cf-tab', tab === 'llamados' ? 'active' : '']" @click="tab = 'llamados'">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
        Llamados
      </button>
    </div>

    <div class="cf-content">
      <!-- USUARIOS -->
      <div v-if="tab === 'usuarios'" class="cf-section">
        <div class="cf-section-h">
          <h3>Usuarios del Sistema</h3>
          <div class="cf-section-actions">
            <button class="cf-btn-add" @click="abrirFormUsuario()">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Nuevo
            </button>
            <span class="cf-count">{{ usuarios.length }} usuario{{ usuarios.length !== 1 ? 's' : '' }}</span>
          </div>
        </div>
        <div v-if="cargandoUsuarios" class="cf-loading">Cargando...</div>
        <div v-else class="cf-user-list">
          <div v-for="u in usuarios" :key="u.id" class="cf-user-card">
            <div class="cf-user-avatar">{{ iniciales(u.name) }}</div>
            <div class="cf-user-info">
              <strong class="cf-user-name">{{ u.name }}</strong>
              <span class="cf-user-email">{{ u.email }}</span>
            </div>
            <span :class="['cf-user-role', u.role === 'admin' ? 'role-admin' : 'role-op']">{{ u.role === 'admin' ? 'Admin' : 'Operador' }}</span>
            <button class="cf-row-btn cf-row-edit" @click="abrirFormUsuario(u)" title="Editar">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </button>
            <button class="cf-row-btn cf-row-del" @click="confirmarEliminarUsuario(u)" title="Eliminar">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
          </div>
          <div v-if="!usuarios.length" class="cf-empty">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            <p>No hay usuarios cargados</p>
          </div>
        </div>
      </div>

      <!-- USUARIO FORM MODAL -->
      <Teleport to="body">
        <Transition name="modal">
          <div v-if="showFormUsuario" class="modal-overlay" @click.self="cerrarFormUsuario">
            <div class="modal-box">
              <div class="modal-top">
                <h3>{{ editandoUsuario ? 'Editar usuario' : 'Nuevo usuario' }}</h3>
                <button class="modal-x" @click="cerrarFormUsuario">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
              </div>
              <div class="form-group">
                <label class="form-label">Nombre</label>
                <div class="input-wrap">
                  <svg class="input-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                  <input class="form-input" v-model="formUsuario.name" placeholder="Nombre completo" />
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">Email</label>
                <div class="input-wrap">
                  <svg class="input-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                  <input class="form-input" v-model="formUsuario.email" placeholder="email@ejemplo.com" type="email" />
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">Contraseña <span class="label-opt">{{ editandoUsuario ? '(dejar vacío para mantener)' : '' }}</span></label>
                <div class="input-wrap">
                  <svg class="input-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                  <input class="form-input" v-model="formUsuario.password" placeholder="••••••••" type="password" />
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">Rol</label>
                <div class="tipo-selector">
                  <label :class="['tipo-opt', { active: formUsuario.role === 'admin' }]">
                    <input type="radio" v-model="formUsuario.role" value="admin" />
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Admin
                  </label>
                  <label :class="['tipo-opt', { active: formUsuario.role === 'user' }]">
                    <input type="radio" v-model="formUsuario.role" value="user" />
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Operador
                  </label>
                </div>
              </div>
              <div v-if="formUsuarioError" class="form-msg error">{{ formUsuarioError }}</div>
              <div class="form-actions">
                <button class="btn btn-ghost" @click="cerrarFormUsuario" :disabled="guardandoUsuario">Cancelar</button>
                <button class="btn btn-primary" @click="guardarUsuario" :disabled="guardandoUsuario || !formUsuarioValido">
                  {{ guardandoUsuario ? 'Guardando...' : (editandoUsuario ? 'Actualizar' : 'Crear') }}
                </button>
              </div>
            </div>
          </div>
        </Transition>
      </Teleport>

      <!-- USUARIO DELETE CONFIRM -->
      <Teleport to="body">
        <Transition name="modal">
          <div v-if="eliminarUsuarioConfirm" class="modal-overlay" @click.self="eliminarUsuarioConfirm = null">
            <div class="modal-box">
              <div class="modal-top">
                <h3>Eliminar usuario</h3>
                <button class="modal-x" @click="eliminarUsuarioConfirm = null">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
              </div>
              <p style="font-size:0.88rem;color:#475569;margin:0 0 1rem">¿Eliminar a <strong>{{ eliminarUsuarioConfirm?.name }}</strong>? Esta acción no se puede deshacer.</p>
              <div v-if="formUsuarioError" class="form-msg error">{{ formUsuarioError }}</div>
              <div class="form-actions">
                <button class="btn btn-ghost" @click="eliminarUsuarioConfirm = null" :disabled="guardandoUsuario">Cancelar</button>
                <button class="btn btn-danger" @click="ejecutarEliminarUsuario" :disabled="guardandoUsuario">{{ guardandoUsuario ? 'Eliminando...' : 'Eliminar' }}</button>
              </div>
            </div>
          </div>
        </Transition>
      </Teleport>

      <!-- SEDES -->
      <div v-if="tab === 'sedes'" class="cf-section">
        <div class="cf-section-h">
          <h3>Sedes</h3>
        </div>
        <div class="cf-sedes-grid">
          <div :class="['cf-sede-card', { activa: sedes[0].id === sedeActiva }]" @click="seleccionarSede(sedes[0])">
            <div class="cf-sede-icon" style="background:#dbeafe;color:#1d4ed8">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div class="cf-sede-body">
              <strong>{{ sedes[0].nombre }}</strong>
              <span class="cf-sede-meta">{{ sedes[0].habitaciones }} habitaciones · {{ sedes[0].servicios }} servicios</span>
            </div>
            <svg class="cf-sede-chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
          </div>
          <div :class="['cf-sede-card', { activa: sedes[1].id === sedeActiva }]" @click="seleccionarSede(sedes[1])">
            <div class="cf-sede-icon" style="background:#fef3c7;color:#d97706">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div class="cf-sede-body">
              <strong>{{ sedes[1].nombre }}</strong>
              <span class="cf-sede-meta">{{ sedes[1].habitaciones }} habitaciones · {{ sedes[1].servicios }} servicios</span>
            </div>
            <svg class="cf-sede-chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
          </div>
        </div>
      </div>

      <!-- SERVICIOS -->
      <div v-if="tab === 'servicios'" class="cf-section">
        <div class="cf-section-h">
          <h3>Servicios / Pisos</h3>
          <span class="cf-count">{{ servicios.length }} piso{{ servicios.length !== 1 ? 's' : '' }}</span>
        </div>
        <div class="cf-serv-list">
          <div v-for="sv in servicios" :key="sv.piso" class="cf-serv-row" @click="abrirModal(sv)">
            <div class="cf-serv-info">
              <strong>{{ sv.piso }}</strong>
              <span class="cf-serv-meta">{{ sv.sedes }} · {{ sv.llamados }} llamados · {{ sv.baños }} baños</span>
            </div>
            <span class="cf-serv-count">{{ sv.total }} hab.</span>
            <div class="cf-serv-bar">
              <div class="cf-serv-fill" :style="{ width: sv.pct + '%' }"></div>
            </div>
            <svg class="cf-serv-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
          </div>
          <div v-if="!servicios.length" class="cf-empty">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><line x1="9" y1="6" x2="10" y2="6"/></svg>
            <p>No hay servicios registrados</p>
          </div>
        </div>
      </div>

      <Teleport to="body">
        <Transition name="modal">
          <div v-if="modalPiso" class="modal-overlay" @click.self="modalPiso = null">
            <div class="modal-box modal-wide">
              <div class="modal-top">
                <h3>{{ modalPiso.piso }}</h3>
                <span class="modal-count">{{ modalPiso.total }} habitaciones</span>
                <button class="modal-x" @click="modalPiso = null">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
              </div>
              <div class="modal-section" style="cursor:pointer" @click="irALlamados(modalPiso.piso, 'llamados')">
                <div class="modal-section-icon icon-call">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </div>
                <div class="modal-section-body">
                  <div class="modal-section-n">Hospitalización Llamados</div>
                  <div class="modal-section-num">{{ modalPiso.llamados }}</div>
                </div>
                <span class="modal-badge badge-blue">{{ modalPiso.llamados }} llamados</span>
              </div>
              <div class="modal-section" style="cursor:pointer" @click="irALlamados(modalPiso.piso, 'baños')">
                <div class="modal-section-icon icon-bath">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h16a1 1 0 0 1 1 1v3a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4v-3a1 1 0 0 1 1-1z"/><path d="M6 12V5a2 2 0 0 1 2-2h3v2.25"/><path d="M4 21l1-1.5"/><path d="M20 21l-1-1.5"/></svg>
                </div>
                <div class="modal-section-body">
                  <div class="modal-section-n">Baños</div>
                  <div class="modal-section-num">{{ modalPiso.baños }}</div>
                </div>
                <span class="modal-badge badge-green">{{ modalPiso.baños }} baños</span>
              </div>
            </div>
          </div>
        </Transition>
      </Teleport>

      <!-- LLAMADOS -->
      <div v-if="tab === 'llamados'" class="cf-section">
        <div class="cf-context" v-if="contextPiso">
          <button class="cf-context-back" @click="limpiarContexto">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            {{ contextPiso }} · {{ contextTipo === 'llamados' ? 'Llamados' : 'Baños' }}
          </button>
          <span class="cf-context-sep">/</span>
          <span class="cf-context-current">{{ roomsFiltered.length }} resultado{{ roomsFiltered.length !== 1 ? 's' : '' }}</span>
        </div>
        <div class="cf-section-h" v-if="!contextPiso">
          <h3>Todos los Llamados</h3>
          <div class="cf-section-actions">
            <button class="cf-btn-add" @click="abrirFormCrear">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Crear
            </button>
            <span class="cf-count">{{ roomsFiltered.length }} llamado{{ roomsFiltered.length !== 1 ? 's' : '' }}</span>
          </div>
        </div>
        <div class="cf-search-bar">
          <svg class="cf-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input class="cf-search-input" v-model="llamadosFiltro" placeholder="Buscar por nombre, sección o ID..." />
        </div>
        <div class="cf-llamados-list">
          <div v-for="r in roomsFiltered" :key="r.id" class="cf-llamado-row">
            <div class="cf-llamado-sede">
              <span :class="['cf-sede-tag', r.sede === 'torre' ? 'sede-torre' : 'sede-urge']">{{ r.sede === 'torre' ? 'T' : 'U' }}</span>
            </div>
            <div class="cf-llamado-info">
              <span class="cf-llamado-name">{{ r.name }}</span>
              <span class="cf-llamado-meta">{{ r.section }}</span>
            </div>
            <span :class="['cf-llamado-type', r.type === 'llamados' ? 'type-call' : 'type-bath']">{{ r.type === 'llamados' ? 'Llamado' : 'Baño' }}</span>
            <button class="cf-row-btn cf-row-edit" @click="abrirFormEditar(r)" title="Editar">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </button>
            <button class="cf-row-btn cf-row-del" @click="confirmarEliminar(r)" title="Eliminar">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
          </div>
          <div v-if="!roomsFiltered.length" class="cf-empty">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            <p>No hay llamados que coincidan</p>
          </div>
        </div>
      </div>

      <Teleport to="body">
        <Transition name="modal">
          <div v-if="showFormLlamado" class="modal-overlay" @click.self="cerrarFormLlamado">
            <div class="modal-box">
              <div class="modal-top">
                <h3>{{ editando ? 'Editar Llamado' : 'Crear Llamado' }}</h3>
                <button class="modal-x" @click="cerrarFormLlamado">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
              </div>
              <div class="form-group">
                <label class="form-label">Sede</label>
                <div class="input-wrap">
                  <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                  <select class="form-input" v-model="formLlamado.sede">
                    <option value="torre">Torre</option>
                    <option value="urgencias">Urgencias</option>
                  </select>
                </div>
              </div>
              <div v-if="!editando" class="form-group">
                <label class="form-label">Piso / Sección</label>
                <div class="input-wrap">
                  <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><line x1="9" y1="6" x2="10" y2="6"/><line x1="14" y1="6" x2="15" y2="6"/><line x1="9" y1="10" x2="10" y2="10"/><line x1="14" y1="10" x2="15" y2="10"/><line x1="9" y1="14" x2="10" y2="14"/><line x1="14" y1="14" x2="15" y2="14"/></svg>
                  <input class="form-input" v-model="formLlamado.piso" placeholder="Ej: Piso 8, UCI..." />
                </div>
              </div>
              <div v-if="!editando" class="form-group">
                <label class="form-label">Área <span class="label-opt">opcional</span></label>
                <div class="input-wrap">
                  <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h16"/><path d="M4 20V4"/><path d="M20 20V8"/><path d="M12 20V12"/><path d="M16 20V4"/></svg>
                  <input class="form-input" v-model="formLlamado.area" placeholder="Hospitalización, Cirugía, UCI..." />
                </div>
              </div>
              <div v-if="editando" class="form-group">
                <label class="form-label">Sección</label>
                <div class="input-wrap">
                  <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><line x1="9" y1="6" x2="10" y2="6"/><line x1="14" y1="6" x2="15" y2="6"/><line x1="9" y1="10" x2="10" y2="10"/><line x1="14" y1="10" x2="15" y2="10"/><line x1="9" y1="14" x2="10" y2="14"/><line x1="14" y1="14" x2="15" y2="14"/></svg>
                  <input class="form-input" v-model="formLlamado.section" placeholder="Sección completa" />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">ID del llamado</label>
                  <div class="input-wrap">
                    <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
                    <input class="form-input" v-model="formLlamado.room_id" placeholder="Ej: 8-ENF" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="form-label">Nombre</label>
                  <div class="input-wrap">
                    <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <input class="form-input" v-model="formLlamado.name" placeholder="Estación Enfermería" />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">Tipo</label>
                <div class="tipo-selector">
                  <label :class="['tipo-opt', { active: formLlamado.type === 'llamados' }]">
                    <input type="radio" v-model="formLlamado.type" value="llamados" />
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    Llamado
                  </label>
                  <label :class="['tipo-opt', { active: formLlamado.type === 'baños' }]">
                    <input type="radio" v-model="formLlamado.type" value="baños" />
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8h16"/><rect x="2" y="8" width="20" height="8" rx="2"/><path d="M6 16v3"/><path d="M18 16v3"/></svg>
                    Baño
                  </label>
                </div>
              </div>
              <div class="form-actions">
                <button class="btn btn-primary" @click="guardarLlamado" :disabled="!formLlamadoValido || guardandoLlamado">
                  <template v-if="guardandoLlamado">Guardando...</template>
                  <template v-else>{{ editando ? 'Guardar cambios' : 'Crear Llamado' }}</template>
                </button>
                <button class="btn btn-ghost" @click="cerrarFormLlamado">Cancelar</button>
              </div>
              <Transition name="msg">
                <div v-if="formError" class="form-msg error">{{ formError }}</div>
              </Transition>
            </div>
          </div>
        </Transition>
      </Teleport>

      <Teleport to="body">
        <Transition name="modal">
          <div v-if="eliminarConfirm" class="modal-overlay" @click.self="eliminarConfirm = null">
            <div class="modal-box" style="width:360px">
              <div class="modal-top">
                <h3>Eliminar Llamado</h3>
                <button class="modal-x" @click="eliminarConfirm = null">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
              </div>
              <p style="margin:0 0 16px;font-size:0.85rem;color:#475569">
                ¿Eliminar <strong>{{ eliminarConfirm?.name }}</strong>?
              </p>
              <div class="form-actions">
                <button class="btn btn-danger" @click="ejecutarEliminar" :disabled="guardandoLlamado">
                  <template v-if="guardandoLlamado">Eliminando...</template>
                  <template v-else>Eliminar</template>
                </button>
                <button class="btn btn-ghost" @click="eliminarConfirm = null">Cancelar</button>
              </div>
              <Transition name="msg">
                <div v-if="formError" class="form-msg error">{{ formError }}</div>
              </Transition>
            </div>
          </div>
        </Transition>
      </Teleport>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useStore } from '../store'
import { api } from '../api'

const store = useStore()
const tab = ref('usuarios')
const usuarios = ref([])
const cargandoUsuarios = ref(true)
const sedeActiva = ref(null)
const servicioActivo = ref(null)

const showFormUsuario = ref(false)
const editandoUsuario = ref(false)
const editandoUsuarioId = ref(null)
const guardandoUsuario = ref(false)
const formUsuarioError = ref('')
const eliminarUsuarioConfirm = ref(null)
const formUsuario = reactive({
  name: '',
  email: '',
  password: '',
  role: 'user',
})

const formUsuarioValido = computed(() => {
  if (editandoUsuario.value) {
    return formUsuario.name && formUsuario.email
  }
  return formUsuario.name && formUsuario.email && formUsuario.password
})

const resetFormUsuario = () => {
  formUsuario.name = ''
  formUsuario.email = ''
  formUsuario.password = ''
  formUsuario.role = 'user'
  formUsuarioError.value = ''
  editandoUsuario.value = false
  editandoUsuarioId.value = null
}

const abrirFormUsuario = (u = null) => {
  resetFormUsuario()
  if (u) {
    editandoUsuario.value = true
    editandoUsuarioId.value = u.id
    formUsuario.name = u.name
    formUsuario.email = u.email
    formUsuario.role = u.role
  }
  showFormUsuario.value = true
}

const cerrarFormUsuario = () => {
  showFormUsuario.value = false
  resetFormUsuario()
}

const cargarUsuarios = async () => {
  try { usuarios.value = await api.getUsers() || [] }
  catch { usuarios.value = [] }
  finally { cargandoUsuarios.value = false }
}

const guardarUsuario = async () => {
  formUsuarioError.value = ''
  guardandoUsuario.value = true
  try {
    if (editandoUsuario.value) {
      const payload = { name: formUsuario.name, email: formUsuario.email, role: formUsuario.role }
      if (formUsuario.password) payload.password = formUsuario.password
      await api.updateUser(editandoUsuarioId.value, payload)
      store.showToast('Usuario actualizado', 'success')
    } else {
      await api.createUser({
        name: formUsuario.name,
        email: formUsuario.email,
        password: formUsuario.password,
        role: formUsuario.role,
      })
      store.showToast('Usuario creado', 'success')
    }
    cerrarFormUsuario()
    await cargarUsuarios()
  } catch (e) {
    formUsuarioError.value = e.message || 'Error al guardar'
  } finally {
    guardandoUsuario.value = false
  }
}

const confirmarEliminarUsuario = (u) => {
  eliminarUsuarioConfirm.value = u
  formUsuarioError.value = ''
}

const ejecutarEliminarUsuario = async () => {
  formUsuarioError.value = ''
  guardandoUsuario.value = true
  try {
    await api.deleteUser(eliminarUsuarioConfirm.value.id)
    store.showToast(`"${eliminarUsuarioConfirm.value.name}" eliminado`, 'success')
    eliminarUsuarioConfirm.value = null
    await cargarUsuarios()
  } catch (e) {
    formUsuarioError.value = e.message || 'Error al eliminar'
  } finally {
    guardandoUsuario.value = false
  }
}

const rooms = computed(() => store.state.rooms)

const iniciales = (name) => {
  const p = (name || '').split(' ')
  return p.length >= 2 ? (p[0][0] + p[p.length - 1][0]).toUpperCase() : (name?.[0] || '?').toUpperCase()
}

const sedes = computed(() => {
  const torre = rooms.value.filter(r => r.sede === 'torre')
  const urgencias = rooms.value.filter(r => r.sede === 'urgencias')
  const seccionesT = new Set(torre.map(r => r.section))
  const seccionesU = new Set(urgencias.map(r => r.section))
  return [
    { id: 'torre', nombre: 'Torre', habitaciones: torre.length, servicios: seccionesT.size, activa: store.state.sede === 'torre' },
    { id: 'urgencias', nombre: 'Urgencias', habitaciones: urgencias.length, servicios: seccionesU.size, activa: store.state.sede === 'urgencias' },
  ]
})

const extraerPiso = (section) => {
  const m = section?.match(/(Piso \d+)/i)
  return m ? m[1] : section || 'Sin piso'
}

const extraerArea = (section) => {
  if (!section) return 'General'
  const sinPiso = section.replace(/Piso \d+\s*[–\-]?\s*/i, '')
  const sinTipo = sinPiso.replace(/\s*[–\-]?\s*(Llamados|Baños)\s*$/i, '').trim()
  return sinTipo || 'General'
}

const servicios = computed(() => {
  const pisos = {}
  const detalle = {}
  rooms.value.forEach(r => {
    const sec = r.section || 'Sin sección'
    const piso = extraerPiso(sec)
    const area = extraerArea(sec)
    if (!detalle[piso]) detalle[piso] = {}
    if (!detalle[piso][area]) detalle[piso][area] = { area, total: 0, tipoLlamados: 0, tipoBanos: 0 }
    detalle[piso][area].total++
    if (r.type === 'llamados') detalle[piso][area].tipoLlamados++
    else detalle[piso][area].tipoBanos++
    if (!pisos[piso]) pisos[piso] = { piso, total: 0, llamados: 0, baños: 0, sedes: new Set() }
    pisos[piso].total++
    if (r.type === 'llamados') pisos[piso].llamados++
    else pisos[piso].baños++
    pisos[piso].sedes.add(r.sede === 'torre' ? 'Torre' : 'Urgencias')
  })
  const max = Math.max(...Object.values(pisos).map(v => v.total), 1)
  const order = ['Piso 9', 'Piso 8', 'Piso 7', 'Piso 6', 'Piso 5', 'Piso 2', 'Piso 1']
  const arr = Object.values(pisos)
  const sorted = []
  order.forEach(o => {
    const i = arr.findIndex(x => x.piso === o)
    if (i >= 0) { sorted.push({ ...arr[i], pct: Math.round(arr[i].total / max * 100), sedes: Array.from(arr[i].sedes).join(', '), detalles: Object.values(detalle[o] || {}) }); arr.splice(i, 1) }
  })
  arr.sort((a, b) => a.piso.localeCompare(b.piso)).forEach(x => sorted.push({ ...x, pct: Math.round(x.total / max * 100), sedes: Array.from(x.sedes).join(', '), detalles: Object.values(detalle[x.piso] || {}) }))
  return sorted
})

const seleccionarSede = (s) => {
  store.setSede(s.id)
  sedeActiva.value = s.id
  servicioActivo.value = null
  tab.value = 'servicios'
}

const llamadosFiltro = ref('')
const contextPiso = ref(null)
const contextTipo = ref(null)

const irALlamados = (piso, tipo) => {
  contextPiso.value = piso
  contextTipo.value = tipo
  llamadosFiltro.value = ''
  modalPiso.value = null
  tab.value = 'llamados'
}

const limpiarContexto = () => {
  contextPiso.value = null
  contextTipo.value = null
  llamadosFiltro.value = ''
}

const roomsFiltered = computed(() => {
  let res = rooms.value
  if (contextPiso.value) {
    res = res.filter(r => (r.section || '').includes(contextPiso.value))
  }
  if (contextTipo.value) {
    res = res.filter(r => r.type === contextTipo.value)
  }
  if (llamadosFiltro.value) {
    const q = llamadosFiltro.value.toLowerCase()
    res = res.filter(r =>
      (r.name || '').toLowerCase().includes(q) ||
      (r.section || '').toLowerCase().includes(q) ||
      (r.room_id || '').toLowerCase().includes(q)
    )
  }
  return res
})

const showFormLlamado = ref(false)
const editando = ref(false)
const editandoId = ref(null)
const guardandoLlamado = ref(false)
const formError = ref('')
const eliminarConfirm = ref(null)

const formLlamado = reactive({
  sede: 'torre',
  piso: '',
  area: '',
  room_id: '',
  name: '',
  type: 'llamados',
  section: '',
})

const formLlamadoValido = computed(() => {
  if (editando.value) return formLlamado.room_id && formLlamado.name && formLlamado.section
  return formLlamado.piso && formLlamado.room_id && formLlamado.name
})

const resetFormLlamado = () => {
  formLlamado.sede = 'torre'
  formLlamado.piso = ''
  formLlamado.area = ''
  formLlamado.room_id = ''
  formLlamado.name = ''
  formLlamado.type = 'llamados'
  formLlamado.section = ''
  formError.value = ''
  editando.value = false
  editandoId.value = null
}

const abrirFormCrear = () => {
  resetFormLlamado()
  if (contextPiso.value) {
    formLlamado.piso = contextPiso.value
    formLlamado.type = contextTipo.value || 'llamados'
  }
  showFormLlamado.value = true
}

const abrirFormEditar = (r) => {
  resetFormLlamado()
  editando.value = true
  editandoId.value = r.id
  formLlamado.sede = r.sede
  formLlamado.section = r.section
  formLlamado.room_id = r.room_id
  formLlamado.name = r.name
  formLlamado.type = r.type
  showFormLlamado.value = true
}

const cerrarFormLlamado = () => {
  showFormLlamado.value = false
  resetFormLlamado()
}

const guardarLlamado = async () => {
  formError.value = ''
  guardandoLlamado.value = true
  try {
    if (editando.value) {
      await api.updateRoom(editandoId.value, {
        room_id: formLlamado.room_id,
        name: formLlamado.name,
        section: formLlamado.section,
        type: formLlamado.type,
        sede: formLlamado.sede,
      })
      store.showToast('Llamado actualizado', 'success')
    } else {
      const section = formLlamado.area
        ? `${formLlamado.piso} - ${formLlamado.area} - ${formLlamado.type === 'llamados' ? 'Llamados' : 'Baños'}`
        : `${formLlamado.piso} - ${formLlamado.type === 'llamados' ? 'Llamados' : 'Baños'}`
      await api.createRoom({
        room_id: formLlamado.room_id,
        name: formLlamado.name,
        section,
        type: formLlamado.type,
        sede: formLlamado.sede,
      })
      store.showToast('Llamado creado', 'success')
    }
    cerrarFormLlamado()
    const roomsData = await api.getRooms()
    store.setRooms(roomsData)
  } catch (e) {
    formError.value = e.message || 'Error al guardar'
  } finally {
    guardandoLlamado.value = false
  }
}

const confirmarEliminar = (r) => {
  eliminarConfirm.value = r
  formError.value = ''
}

const ejecutarEliminar = async () => {
  formError.value = ''
  guardandoLlamado.value = true
  try {
    await api.deleteRoom(eliminarConfirm.value.id)
    store.showToast(`"${eliminarConfirm.value.name}" eliminado`, 'success')
    eliminarConfirm.value = null
    const roomsData = await api.getRooms()
    store.setRooms(roomsData)
  } catch (e) {
    formError.value = e.message || 'Error al eliminar'
  } finally {
    guardandoLlamado.value = false
  }
}

const modalPiso = ref(null)
const abrirModal = (sv) => { modalPiso.value = sv }

onMounted(async () => {
  await cargarUsuarios()
})
</script>

<style scoped>
.cf-wrap {
  padding: 24px 28px;
}
.cf-hero {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 20px;
  animation: f 0.4s ease backwards;
}
.cf-hero-icon {
  width: 48px;
  height: 48px;
  border-radius: 14px;
  background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
  color: #4338ca;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.cf-hero-title {
  font-size: 1.3rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0;
}
.cf-hero-desc {
  font-size: 0.85rem;
  color: #64748b;
  margin: 2px 0 0;
}
.cf-tabs {
  display: flex;
  gap: 4px;
  margin-bottom: 20px;
  border-bottom: 1px solid #e2e8f0;
  animation: f 0.4s 0.05s ease backwards;
}
.cf-tab {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 10px 16px;
  border: none;
  background: none;
  font-size: 0.82rem;
  font-weight: 600;
  color: #64748b;
  cursor: pointer;
  border-bottom: 2px solid transparent;
  margin-bottom: -1px;
  transition: all 0.15s;
  font-family: inherit;
}
.cf-tab:hover { color: #0f172a; background: #f8fafc; }
.cf-tab.active { color: #4f46e5; border-bottom-color: #4f46e5; }
.cf-tab svg { opacity: 0.6; }
.cf-tab.active svg { opacity: 1; }

.cf-content {
  animation: f 0.4s 0.08s ease backwards;
}
.cf-section {
  min-height: 200px;
}
.cf-section-h {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 14px;
}
.cf-section-h h3 {
  margin: 0;
  font-size: 1rem;
  font-weight: 700;
  color: #0f172a;
}
.cf-count {
  font-size: 0.72rem;
  font-weight: 600;
  color: #64748b;
  background: #f1f5f9;
  padding: 3px 10px;
  border-radius: 100px;
}
.cf-loading {
  text-align: center;
  padding: 40px;
  color: #94a3b8;
  font-size: 0.85rem;
}
.cf-empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  padding: 40px;
  color: #94a3b8;
  font-size: 0.85rem;
}

/* USUARIOS */
.cf-user-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.cf-user-card {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 14px;
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  transition: all 0.15s;
}
.cf-user-card:hover {
  border-color: #cbd5e1;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.cf-user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
  color: #4338ca;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  font-size: 0.85rem;
  flex-shrink: 0;
}
.cf-user-info {
  flex: 1;
  min-width: 0;
}
.cf-user-name {
  display: block;
  font-size: 0.88rem;
  color: #0f172a;
}
.cf-user-email {
  font-size: 0.75rem;
  color: #94a3b8;
}
.cf-user-role {
  font-size: 0.62rem;
  font-weight: 700;
  padding: 3px 10px;
  border-radius: 100px;
  text-transform: uppercase;
  letter-spacing: 0.03em;
}
.role-admin { background: #e0e7ff; color: #4338ca; }
.role-op { background: #d1fae5; color: #059669; }

/* SEDES */
.cf-sedes-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
}
.cf-sede-card {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 18px 20px;
  background: #fff;
  border: 1.5px solid #e2e8f0;
  border-radius: 14px;
  transition: all 0.2s;
}
.cf-sede-card:hover {
  border-color: #cbd5e1;
  box-shadow: 0 4px 16px rgba(0,0,0,0.05);
}
.cf-sede-card.activa {
  border-color: #4f46e5;
  box-shadow: 0 0 0 2px rgba(79,70,229,0.08);
}
.cf-sede-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.cf-sede-body {
  flex: 1;
}
.cf-sede-body strong {
  display: block;
  font-size: 1rem;
  color: #0f172a;
}
.cf-sede-meta {
  font-size: 0.75rem;
  color: #94a3b8;
  margin-top: 2px;
}
.cf-sede-badge {
  font-size: 0.6rem;
  font-weight: 700;
  padding: 3px 10px;
  border-radius: 100px;
  background: #e0e7ff;
  color: #4338ca;
  text-transform: uppercase;
  letter-spacing: 0.03em;
}
.cf-sede-card {
  cursor: pointer;
}
.cf-sede-chev {
  color: #94a3b8;
  flex-shrink: 0;
  transition: all 0.15s;
}
.cf-sede-card:hover .cf-sede-chev {
  color: #4f46e5;
  transform: translateX(3px);
}

/* CONTEXT */
.cf-context {
  display: flex;
  align-items: center;
  gap: 6px;
  margin-bottom: 12px;
  font-size: 0.8rem;
}
.cf-context-back {
  display: inline-flex;
  align-items: center;
  gap: 3px;
  padding: 3px 8px 3px 4px;
  border: none;
  border-radius: 6px;
  background: #f1f5f9;
  color: #64748b;
  font-size: 0.75rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.12s;
  font-family: inherit;
}
.cf-context-back:hover {
  background: #e2e8f0;
  color: #0f172a;
}
.cf-context-sep {
  color: #cbd5e1;
  font-weight: 300;
}
.cf-context-current {
  font-weight: 700;
  color: #0f172a;
}

/* SERVICIOS */
.cf-serv-list {
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.cf-serv-row {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 14px;
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  transition: all 0.15s;
}
.cf-serv-row:hover {
  border-color: #cbd5e1;
}
.cf-serv-info {
  flex: 1;
  min-width: 0;
}
.cf-serv-info strong {
  display: block;
  font-size: 0.85rem;
  color: #0f172a;
}
.cf-serv-meta {
  font-size: 0.7rem;
  color: #94a3b8;
}
.cf-serv-count {
  font-size: 0.78rem;
  font-weight: 700;
  color: #475569;
  min-width: 36px;
  text-align: right;
}
.cf-serv-bar {
  width: 80px;
  height: 6px;
  background: #f1f5f9;
  border-radius: 4px;
  overflow: hidden;
  flex-shrink: 0;
}
.cf-serv-fill {
  height: 100%;
  border-radius: 4px;
  background: linear-gradient(90deg, #4f46e5, #6366f1);
  transition: width 0.4s ease;
}

.cf-serv-row {
  cursor: pointer;
}
.cf-serv-chev {
  color: #94a3b8;
  flex-shrink: 0;
  transition: all 0.15s;
}
.cf-serv-row:hover .cf-serv-chev {
  color: #4f46e5;
  transform: translateX(3px);
}

/* MODAL */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(15,23,42,0.4);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 1rem;
}
.modal-box {
  background: #fff;
  border-radius: 16px;
  padding: 1.5rem 1.75rem;
  width: 420px;
  max-width: 90vw;
  max-height: 70vh;
  overflow-y: auto;
  box-shadow: 0 25px 60px rgba(0,0,0,0.15);
}
.modal-top {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 1rem;
}
.modal-top h3 {
  margin: 0;
  font-size: 1.1rem;
  font-weight: 700;
  color: #0f172a;
}
.modal-count {
  font-size: 0.72rem;
  font-weight: 600;
  color: #64748b;
  background: #f1f5f9;
  padding: 2px 10px;
  border-radius: 100px;
}
.modal-x {
  margin-left: auto;
  background: none;
  border: none;
  cursor: pointer;
  color: #94a3b8;
  padding: 4px;
  border-radius: 6px;
  display: flex;
  transition: all 0.15s;
}
.modal-x:hover {
  background: #f1f5f9;
  color: #0f172a;
}
.modal-wide {
  width: 500px;
}
.modal-section {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 16px 18px;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  margin-bottom: 10px;
  transition: all 0.2s;
  background: #fff;
}
.modal-section:hover {
  border-color: #cbd5e1;
  box-shadow: 0 4px 16px rgba(0,0,0,0.05);
  transform: translateY(-1px);
}
.modal-section-icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.icon-call {
  background: linear-gradient(135deg, #dbeafe, #bfdbfe);
  color: #1d4ed8;
}
.icon-bath {
  background: linear-gradient(135deg, #d1fae5, #a7f3d0);
  color: #059669;
}
.modal-section-body {
  flex: 1;
  min-width: 0;
}
.modal-section-n {
  font-size: 0.78rem;
  font-weight: 600;
  color: #64748b;
  letter-spacing: 0.01em;
}
.modal-section-num {
  font-size: 1.6rem;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.2;
  margin-top: 2px;
}
.modal-badge {
  font-size: 0.68rem;
  font-weight: 700;
  padding: 4px 14px;
  border-radius: 100px;
  white-space: nowrap;
  flex-shrink: 0;
}
.badge-blue {
  background: #dbeafe;
  color: #1d4ed8;
}
.badge-green {
  background: #d1fae5;
  color: #059669;
}

/* LLAMADOS LIST */
.cf-section-actions {
  display: flex;
  align-items: center;
  gap: 8px;
}
.cf-btn-add {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 5px 12px;
  border: none;
  border-radius: 8px;
  background: linear-gradient(135deg, #3b82f6, #2563eb);
  color: #fff;
  font-size: 0.72rem;
  font-weight: 700;
  cursor: pointer;
  transition: all .15s;
  font-family: inherit;
  box-shadow: 0 2px 8px rgba(59,130,246,0.2);
}
.cf-btn-add:hover {
  box-shadow: 0 4px 14px rgba(59,130,246,0.3);
  transform: translateY(-1px);
}
.cf-row-btn {
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: all .12s;
  flex-shrink: 0;
  background: transparent;
}
.cf-row-edit { color: #64748b; }
.cf-row-edit:hover { background: #eff6ff; color: #3b82f6; }
.cf-row-del { color: #64748b; }
.cf-row-del:hover { background: #fef2f2; color: #ef4444; }

/* FORM STYLES */
.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  flex: 1;
  margin-bottom: 0.75rem;
}
.form-label {
  font-size: 0.78rem;
  font-weight: 700;
  color: #334155;
  text-transform: uppercase;
  letter-spacing: 0.03em;
}
.label-opt {
  font-weight: 400;
  color: #94a3b8;
  text-transform: none;
  letter-spacing: 0;
}
.input-wrap {
  position: relative;
  display: flex;
  align-items: center;
}
.input-icon {
  position: absolute;
  left: 0.75rem;
  color: #94a3b8;
  pointer-events: none;
  flex-shrink: 0;
}
.form-input {
  padding: 0.6rem 0.75rem 0.6rem 2.25rem;
  border: 1.5px solid #e9edf4;
  border-radius: 10px;
  font-size: 0.88rem;
  background: #f8fafc;
  color: #0f172a;
  width: 100%;
  transition: all .15s;
  font-family: inherit;
  box-sizing: border-box;
}
.form-input:focus {
  outline: none;
  border-color: #3b82f6;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}
select.form-input {
  cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 0.7rem center;
  padding-right: 2.25rem;
}
.form-row {
  display: flex;
  gap: 0.75rem;
}
.tipo-selector {
  display: flex;
  gap: 0.5rem;
}
.tipo-opt {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
  padding: 0.55rem 0.5rem;
  border: 1.5px solid #e9edf4;
  border-radius: 10px;
  font-size: 0.85rem;
  font-weight: 600;
  color: #64748b;
  cursor: pointer;
  transition: all .15s;
  background: #f8fafc;
}
.tipo-opt input { display: none; }
.tipo-opt.active {
  border-color: #3b82f6;
  background: #eff6ff;
  color: #3b82f6;
}
.tipo-opt:not(.active):hover {
  border-color: #cbd5e1;
  background: #f1f5f9;
}
.form-actions {
  display: flex;
  gap: 0.75rem;
  margin-top: 0.35rem;
}
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
  padding: 0.65rem 1.35rem;
  border: none;
  border-radius: 10px;
  font-size: 0.88rem;
  font-weight: 700;
  cursor: pointer;
  transition: all .15s;
  font-family: inherit;
}
.btn-primary {
  flex: 1;
  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
  color: #fff;
  box-shadow: 0 4px 12px rgba(59,130,246,0.25);
}
.btn-primary:hover:not(:disabled) {
  box-shadow: 0 6px 20px rgba(59,130,246,0.35);
  transform: translateY(-1px);
}
.btn-primary:active:not(:disabled) {
  transform: translateY(0);
  box-shadow: 0 2px 8px rgba(59,130,246,0.25);
}
.btn-primary:disabled {
  opacity: .45;
  cursor: not-allowed;
}
.btn-danger {
  flex: 1;
  background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
  color: #fff;
  box-shadow: 0 4px 12px rgba(239,68,68,0.25);
}
.btn-danger:hover:not(:disabled) {
  box-shadow: 0 6px 20px rgba(239,68,68,0.35);
  transform: translateY(-1px);
}
.btn-danger:active:not(:disabled) {
  transform: translateY(0);
}
.btn-ghost {
  background: transparent;
  color: #64748b;
  border: 1.5px solid #e9edf4;
}
.btn-ghost:hover {
  background: #f8fafc;
  color: #0f172a;
  border-color: #cbd5e1;
}
.form-msg {
  padding: 0.65rem 0.9rem;
  border-radius: 10px;
  font-size: 0.82rem;
  font-weight: 500;
  margin-top: 0.5rem;
}
.form-msg.error {
  background: #fef2f2;
  color: #dc2626;
  border: 1px solid #fecaca;
}
.form-msg.exito {
  background: #f0fdf4;
  color: #16a34a;
  border: 1px solid #bbf7d0;
}
.msg-enter-active, .msg-leave-active { transition: all 0.2s ease; }
.msg-enter-from, .msg-leave-to { opacity: 0; transform: translateY(-6px); }

.cf-search-bar {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 14px;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  margin-bottom: 12px;
  transition: all 0.15s;
}
.cf-search-bar:focus-within {
  border-color: #4f46e5;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(79,70,229,0.08);
}
.cf-search-icon { color: #94a3b8; flex-shrink: 0; }
.cf-search-input {
  border: none;
  background: none;
  outline: none;
  flex: 1;
  font-size: 0.82rem;
  color: #0f172a;
  font-family: inherit;
}
.cf-search-input::placeholder { color: #94a3b8; }
.cf-llamados-list {
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.cf-llamado-row {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 14px;
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  transition: all 0.15s;
}
.cf-llamado-row:hover {
  border-color: #cbd5e1;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.cf-llamado-sede {
  flex-shrink: 0;
}
.cf-sede-tag {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  border-radius: 8px;
  font-size: 0.65rem;
  font-weight: 800;
}
.sede-torre { background: #dbeafe; color: #1d4ed8; }
.sede-urge { background: #fef3c7; color: #d97706; }
.cf-llamado-info {
  flex: 1;
  min-width: 0;
}
.cf-llamado-name {
  display: block;
  font-size: 0.83rem;
  font-weight: 600;
  color: #0f172a;
}
.cf-llamado-meta {
  font-size: 0.7rem;
  color: #94a3b8;
  margin-top: 1px;
}
.cf-llamado-type {
  font-size: 0.6rem;
  font-weight: 700;
  padding: 3px 10px;
  border-radius: 100px;
  text-transform: uppercase;
  letter-spacing: 0.03em;
  flex-shrink: 0;
}
.type-call { background: #e0e7ff; color: #4338ca; }
.type-bath { background: #d1fae5; color: #059669; }

.modal-enter-active { transition: all 0.2s ease; }
.modal-leave-active { transition: all 0.15s ease; }
.modal-enter-from { opacity: 0; }
.modal-enter-from .modal-box { transform: scale(0.95) translateY(10px); }
.modal-leave-to { opacity: 0; }
.modal-leave-to .modal-box { transform: scale(0.95) translateY(10px); }
.modal-box { transition: transform 0.2s ease; }

@keyframes f {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>

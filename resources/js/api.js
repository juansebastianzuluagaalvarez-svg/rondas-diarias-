import { useStore } from './store'

const API_BASE = '/api'

const getHeaders = () => {
  const store = useStore()
  const headers = { 'Content-Type': 'application/json' }
  if (store.state.token) {
    headers['Authorization'] = `Bearer ${store.state.token}`
  }
  return headers
}

const request = async (url, options = {}) => {
  const response = await fetch(`${API_BASE}${url}`, {
    ...options,
    headers: {
      ...getHeaders(),
      ...options.headers
    }
  })

  if (!response.ok) {
    const errorData = await response.json().catch(() => ({}))
    throw new Error(errorData.message || `Error ${response.status}`)
  }

  if (response.status === 204) return null
  return response.json()
}

export const api = {
  // Auth
  login: (credentials) => request('/login', {
    method: 'POST',
    body: JSON.stringify(credentials)
  }),
  logout: () => request('/logout', { method: 'POST' }),
  getUser: () => request('/user'),

  // Rondas
  getTodayRonda: () => request('/rondas/today'),
  updateRoomState: (rondaId, roomId, data) => request(`/rondas/${rondaId}/rooms/${roomId}/state`, {
    method: 'POST',
    body: JSON.stringify(data)
  }),
  finalizeRonda: (rondaId) => request(`/rondas/${rondaId}/finalize`, { method: 'POST' }),
  reopenRonda: (rondaId) => request(`/rondas/${rondaId}/reopen`, { method: 'POST' }),

  // Historial
  getHistorial: (filters = {}) => {
    const params = new URLSearchParams(filters)
    return request(`/historial?${params}`)
  },
  updateHistorial: (id, data) => request(`/historial/${id}`, {
    method: 'PUT',
    body: JSON.stringify(data)
  }),
  deleteHistorial: (id) => request(`/historial/${id}`, { method: 'DELETE' }),
  downloadHistorialPdf: async (filters = {}) => {
    const store = useStore()
    const params = new URLSearchParams(filters)
    const response = await fetch(`${API_BASE}/historial/pdf?${params}`, {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${store.state.token}`
      }
    })

    if (!response.ok) {
      throw new Error('Error al descargar el PDF')
    }

    // Crear un blob y descargarlo
    const blob = await response.blob()
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `historial_llamados_${new Date().toISOString().split('T')[0]}.pdf`
    document.body.appendChild(a)
    a.click()
    window.URL.revokeObjectURL(url)
    a.remove()
  }
}

import Vapi from 'vuex-rest-api'

const user = new Vapi({
  baseURL: 'http://ponudbe.local/api/public',
  state: {
    user: {
      token: localStorage.getItem('token')
    }
  }})
  .post({
    action: 'loginUser',
    path: '/login'})
  .post({
    action: 'logoutUser',
    path: '/logout'})
.getStore({
  createStateFn: true
})

user.mutations['saveToken'] = (state, payload) => {
  state.user.token = payload.token
  localStorage.setItem('token', payload.token)
}

user.mutations['logOutUser'] = (state) => {
  localStorage.removeItem('token')
  state.user.token = null
}

export default user

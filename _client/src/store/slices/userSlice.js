import {createSlice, createAsyncThunk} from '@reduxjs/toolkit'
import axios from '../../api/axios'
import {toast} from "react-toastify";
import {AUTH_TOKEN, LOGIN_URL, REGISTER_URL} from '../../api/_const'

const initialState = {
  profileData: {},
  isAuth: false,
}

export const regUser = createAsyncThunk('userSlice/regUser', async (data, {rejectWithValue}) => {
  const response = await axios.post(REGISTER_URL, JSON.stringify(data), {
    headers: {'Content-Type': 'application/json'}
  }).catch(err => {
    toast.error('Системная ошибка', {
      position: toast.POSITION.TOP_RIGHT,
      autoClose: 3000
    });
  })

  if (response.status < 200 || response.status >= 300) {
    return rejectWithValue(response?.data)
  }
  return response.data;
})

export const loginUser = createAsyncThunk('userSlice/loginUser', async (data, {rejectWithValue}) => {
  const response = await axios.post(LOGIN_URL, JSON.stringify(data), {
    headers: {'Content-Type': 'application/json'}
  })

  if (response.status < 200 || response.status >= 300) {
    return rejectWithValue(response?.data)
  }

  return await response?.data
})

const userSlice = createSlice({
  name: 'userSlice',
  initialState,

  reducers: {
    storeUser(state, action) {
      state.profileData = action.payload
    },

    logout(state) {
      state.profileData = {}
      state.isAuth = false

      localStorage.removeItem(AUTH_TOKEN)
      toast.success('Сессия закончена', {
        position: toast.POSITION.TOP_RIGHT,
        autoClose: 3000
      });
    }
  },

  extraReducers: builder => {
    builder
        .addCase(regUser.fulfilled, (state, action) => {
          // console.log('registered')
        })
        .addCase(regUser.rejected, (state, action) => {
          // console.log('reg rejected')
        })

        .addCase(loginUser.fulfilled, (state, action) => {
          localStorage.setItem(AUTH_TOKEN, JSON.stringify(action.payload))
          state.isAuth = true

          toast.success(action.payload.message, {
            position: toast.POSITION.TOP_RIGHT,
            autoClose: 3000
          });
        })

        .addCase(loginUser.rejected, (state, action) => {
          if (action.payload.status == 500) {
            toast.error('Server error', {
              position: toast.POSITION.TOP_RIGHT,
              autoClose: 2000
            });
          } else {
            toast.error(action.payload.message, {
              position: toast.POSITION.TOP_RIGHT,
              autoClose: 2000
            });
          }

        })
  }
})

export const {logout, storeUser} = userSlice.actions
export default userSlice.reducer

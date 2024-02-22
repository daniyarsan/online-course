import { createSlice } from '@reduxjs/toolkit';

const counterSlice = createSlice({
  name: 'counter',
  initialState: {
    counterCollection: [],
  },

  reducers: {
    addCounter: (state, action) => {
      state.counterCollection.push(action.payload);
    },

    updateCounter: (state, action) => {
      const index = state.counterCollection.findIndex(counterItem => counterItem.id == action.payload.id)
      if (index !== -1) {
        state.counterCollection[index] = action.payload
      }
    },

    removeCounter: (state, action) => {
      const index = state.counterCollection.findIndex(counter => counter.id == action.payload)
      if (index !== -1) {
        state.counterCollection.splice(index, 1);
      }
    },
  },
});

export const { addCounter, updateCounter, removeCounter } = counterSlice.actions;
export default counterSlice.reducer
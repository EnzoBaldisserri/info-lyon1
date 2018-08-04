import React from 'react';

const AbsenceContext = React.createContext({
  dataHolder: null,
});

export const AbsenceProvider = AbsenceContext.Provider;
export const AbsenceConsumer = AbsenceContext.Consumer;

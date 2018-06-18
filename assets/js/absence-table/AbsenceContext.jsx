import React from 'react';

const AbsenceContext = React.createContext({
  months: [],
  groups: [],
  absenceTypes: [],
});

export const AbsenceProvider = AbsenceContext.Provider;
export const AbsenceConsumer = AbsenceContext.Consumer;

export default AbsenceContext;

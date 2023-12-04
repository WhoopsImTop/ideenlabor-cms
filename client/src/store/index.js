import { createStore } from "vuex";

export default createStore({
  state: {
    site: {
      pageContent: {
        components: [
          {
            type: "row",
            label: "Reihe",
            component: [
              {
                type: "col",
                label: "Spalte",
                components: [
                  {
                    type: "text",
                    label: "Warte auf die Daten...",
                  },
                ],
              },
            ],
          },
        ],
      },
    },
    customerCenter: {
      loggedIn: false,
      data: {},
    },
  },
});

Vue.component("general-list", {
  template: "#general-list-template",
  data: function () {
    return {
      module: "bookings/inquiries",
      editUrl: _s("editUrl"),
      detailUrl: _s("detailUrl"),
    };
  },
  methods: {
    handleConfirm: function (id) {
      this.updateStatus(id, "confirm");
    },
    handleReject: function (id) {
      this.updateStatus(id, "reject");
    },
    handleCancel: function (id) {
      this.updateStatus(id, "cancel");
    },
    updateStatus: function (id, method) {
      var data = {
        module: this.module,
        method: method,
        id: id,
      };

      var request = submitRequest(data, "post");
      request.then(function (response) {
        //alert(response.message);
        window.location.reload();
      });
    }
  },
  created: function () {
  },
  mounted: function () {
    //loadDataTable();
    //console.log(Codebase);
    //codebase.blocks('#block-list', 'state_loading');
  },
});

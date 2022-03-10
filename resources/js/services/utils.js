export default {
    token: function () {
      return localStorage.getItem('token');
    },
    user: function () {
      return JSON.parse(localStorage.getItem('user'));
    },
    validatePermission: function (permissions, permission) {
  
      // console.log("value :", permission);
      // console.log("type :", typeof permission);

      permission = permission.replace("-",".");

      if (permissions === undefined) {
        permissions = this.user().permissions;
      }
  
      var search = permissions.filter((filtro) => {
        return filtro.name.match(permission);
      });
  
      return search.length > 0 ? true : false;
    }
  };
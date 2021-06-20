class AppStorage {

    storeToken (token) {
        localStorage.setItem('token', token);
    }

    storeUser (user) {
        localStorage.setItem('user', user)
    }

    storeIsUserAdmin(isAdmin) {
        localStorage.setItem('isUserAdmin', isAdmin)
    }

    store (token, user, isAdmin) {
        this.storeToken(token);
        this.storeUser(user);
        this.storeIsUserAdmin(isAdmin);
    }

    clear () {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        localStorage.removeItem('isUserAdmin');
    }

    getToken() {
        localStorage.getItem('token');
    }

    getUser() {
        localStorage.getItem('user');
    }

    getIsAdmin() {
        localStorage.getItem('isUserAdmin');
    }

}

export default AppStorage = new AppStorage();

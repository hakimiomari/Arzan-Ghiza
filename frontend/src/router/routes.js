import BProducts from "../pages/Product/Products.vue";

const routes = [
  {
    path: "/",
    component: () => import("layouts/MainLayout.vue"),
    beforeEnter: (to, from, next) => {
      const user = JSON.parse(localStorage.getItem("user"));
      if (user?.roles == 3) {
        next("authenticated");
      } else if (user?.roles == 1) {
        next("admin");
      } else if (user?.roles == 2) {
        next("dashboard");
      } else {
        next();
      }
    },

    children: [
      {
        path: "",
        component: () => import("../pages/Product/Products.vue"),
        name: "home",
      },
      {
        path: "/register",
        component: () => import("../pages/login/SignUp.vue"),
        name: "register",
      },

      {
        path: "/about",
        component: () => import("../pages/About/AboutComponent.vue"),
        name: "about",
      },
      {
        path: "/services",
        component: () => import("pages/Services/Services.vue"),
        name: "services",
      },
      {
        path: "/product-details/:id",
        component: () => import("../pages/Product/ProductDetails2.vue"),
        name: "Details",
      },
    ],
  },

  // forget password
  {
    path: "/forgetPassword",
    component: () => import("../pages/login/ForgetPassword.vue"),
    name: "forgetPassword",
  },
  {
    path: "/verify-otp",
    component: () => import("../pages/login/SendVerification.vue"),
    name: "verify_otp",
  },
  {
    path: "/change_password",
    component: () => import("../pages/login/NewPassword.vue"),
    name: "change_password",
  },
  // authentication///////////////////////////////////////////
  {
    path: "/authenticated",
    component: () => import("layouts/AuthLayout.vue"),
    beforeEnter: (to, from, next) => {
      const isAuth = JSON.parse(localStorage.getItem("user"));
      if (isAuth?.roles == 3 && isAuth?.token) {
        next();
      } else {
        next("/");
      }
    },
    children: [
      {
        path: "",
        component: BProducts,
        name: "auth",
      },
      {
        path: "/auth/product-details/:id",
        component: () => import("../pages/Product/ProductDetails2.vue"),
        name: "Product-Details",
      },
      {
        path: "/profile",
        component: () => import("pages/Porfile/userProfile.vue"),
        name: "profile",
      },
      {
        path: "/updateProfile",
        component: () => import("../pages/Porfile/UpdateProfile.vue"),
        name: "update-profile",
      },
      {
        path: "/changeUserPassword",
        component: () => import("pages/Porfile/ResetPassword2.vue"),
        name: "changePassword",
      },
      {
        path: "/success",
        component: () => import("pages/Product/success.vue"),
        name: "success",
      },
      {
        path: "/cancel",
        component: () => import("pages/Product/cancel.vue"),
        name: "cancel",
      },
      {
        path: "/auth-about",
        component: () => import("../pages/About/AboutComponent.vue"),
        name: "auth-about",
      },
      {
        path: "/auth-services",
        component: () => import("pages/Services/Services.vue"),
        name: "auth-services",
      },
      {
        path: "/create-bussiness-account",
        component: () => import("pages/Porfile/CreateBussinessAccount.vue"),
        name: "bussiness-account",
      },
      {
        path: "/checkout/:id",
        component: () => import("../pages/Product/StripeCheckout.vue"),
        name: "checkout",
      },
    ],
  },

  //dashboard /////////////////////////////////////////////
  {
    path: "/dashboard",
    component: () => import("../layouts/BusssinessMainLayout.vue"),
    beforeEnter: (to, from, next) => {
      const isAuth = JSON.parse(localStorage.getItem("user"));
      if (isAuth?.roles == 2) {
        next();
      } else {
        next("/");
      }
    },
    children: [
      {
        path: "",
        component: () => import("pages/Dashboard/Dashboard.vue"),
        name: "bussiness_dashboard",
      },
      {
        path: "/calendar",
        component: () => import("../pages/Calendar/Calendar.vue"),
        name: "calendar",
      },
      {
        path: "/add-post",
        component: () => import("../pages/Product/AddProduct.vue"),
        name: "add-post",
      },
      {
        path: "/edit-post/:id",
        component: () => import("../pages/Product/EditProduct.vue"),
        name: "edit-post",
      },
      {
        path: "/bussiness-profile",
        component: () => import("../pages/BProfile/businessProfile.vue"),
        name: "bussiness-profile",
      },
      {
        path: "/update-bussiness-profile",
        component: () => import("../pages/BProfile/UpdateBussinessProfile.vue"),
        name: "update-bussiness-profile",
      },
      {
        path: "/reset-bussiness-profile-password",
        component: () => import("../pages/BProfile/ResetPassword.vue"),
        name: "reset-password",
      },
      {
        path: "/bussiness/sales",
        component: () => import("../pages/BProfile/BSale.vue"),
        name: "sale",
      },
    ],
  },
  // admin routes///////////////////////////////
  {
    path: "/admin",
    component: () => import("../layouts/AdminMainLayout.vue"),
    beforeEnter: (to, from, next) => {
      const isAuth = JSON.parse(localStorage.getItem("user"));
      if (isAuth?.roles == 1) {
        next();
      } else {
        next("/");
      }
    },
    children: [
      {
        path: "",
        component: () => import("../pages/Admin/AdminDashboard.vue"),
      },
      {
        path: "/admin-profile",
        component: () => import("../pages/Porfile/userProfile.vue"),
        name: "admin-profile",
      },
      {
        path: "/admin-updateProfile",
        component: () => import("../pages/Porfile/updateProfile.vue"),
        name: "admin-update-profile",
      },
      {
        path: "/admin-changeUserPassword",
        component: () => import("../pages/Porfile/ResetPassword2.vue"),
        name: "admin-changePassword",
      },
      // //////////////////////////////////
      {
        path: "/edit-product/:id",
        component: () => import("../pages/Admin/EditPost.vue"),
        name: "edit-product",
      },
      {
        path: "/admin-calendar",
        component: () => import("../pages/Calendar/Calendar.vue"),
        name: "admin-calendar",
      },
      {
        path: "/users",
        component: () => import("../pages/Admin/Users.vue"),
        name: "users",
      },
      {
        path: "/update-user-Profile/:id",
        component: () => import("../pages/Admin/UpdateUserProfile.vue"),
        name: "update-user-profile",
      },
      {
        path: "/add_new_user",
        component: () => import("../pages/Admin/addUser.vue"),
        name: "add-new-user",
      },
      {
        path: "/change-user-password/:id",
        component: () => import("../pages/Admin/ChangeUserPassword.vue"),
        name: "change-user-password",
      },
      {
        path: "/bussiness-users",
        component: () => import("../pages/Admin/BussinessUser.vue"),
        name: "bussiness-user",
      },
      {
        path: "/add-bussiness-users",
        component: () => import("../pages/Admin/AddBussinessUser.vue"),
        name: "add-bussiness-users",
      },
      {
        path: "/update-bussiness-user/:id",
        component: () => import("../pages/Admin/UpdateBussinessUser.vue"),
        name: "update-bussiness-user",
      },
      {
        path: "/change-bussiness-user-password/:id",
        component: () => import("../pages/Admin/ChangeBussinessPassword.vue"),
        name: "change-bussiness-user-password",
      },
      {
        path: "/bussiness/products/:id",
        component: () => import("../pages/Admin/OneBussinessProducts.vue"),
        name: "bussiness_products",
      },
      {
        path: "/admin/sales",
        component: () => import("../pages/Admin/Sales.vue"),
        name: "sales",
      },
    ],
  },
  // product details
  {
    path: "/bussiness/product/details/:id",
    component: () => import("../pages/Product/ProductDetails.vue"),
    name: "bussiness_product_details",
  },
  // //////////////////////
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/ErrorNotFound.vue"),
  },
];

export default routes;

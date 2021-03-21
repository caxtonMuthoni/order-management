<template>
  <div class="container row justify-content-center">
    <div class="col-md-9">
      <div class="card mt-3">
        <div class="card-header card-header-content">
          <h3 class="blue card-heading">
            <i class="fab fa-product-hunt"></i> {{ title }} Order products
          </h3>
          <a href="" @click.prevent="showModal()" class="btn btn-sm btn-success"
            ><i class="fas fa-plus mr-2"></i>Add New Product</a
          >
        </div>
        <div class="card-body">
          <table class="table table-striped">
            <thead>
              <th>#</th>
              <th>Product</th>
              <th>Product Description</th>
              <th>Delete</th>
            </thead>
            <tbody>
              <tr v-for="(product, index) in products" :key="product.id">
                <td>{{ index + 1 }}</td>
                <td>{{ product.product.name }}</td>
                <td>{{ product.product.description }}</td>
                <td>
                  <a
                    href=""
                    @click.prevent="deleteOrderProduct(product.id)"
                    class="btn btn-sm btn-danger"
                    ><i class="fas fa-trash"></i
                  ></a>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <th>#</th>
              <th>Product</th>
              <th>Product Description</th>
              <th>Delete</th>
            </tfoot>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="order-products-modal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modelTitleId"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title blue">
              <i class="fas fa-plus mr-2"></i> Adding new order products
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="addOrderProduct()">
            <div class="modal-body">
              <label for="search">Type to Search for the product</label>
              <v-select
                id="search"
                label="name"
                @search="searchProducts"
                :options="options"
                class="mt-1"
                v-model="selectedProduct"
              >
                <template slot="no-options">
                  Type to search supplier products..
                </template>
              </v-select>
              <has-error :form="form" field="products_id"></has-error>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">
                Close
              </button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Form } from "vform";
import Toast from "../common/toast";
export default {
  data() {
    return {
      products: [],
      title: "",
      options: [],
      selectedProduct: {},
      form: new Form({
        order_id: null,
        product_id: null,
      }),
    };
  },

  async mounted() {
    this.getOrderProducts();

    if (!this.$store.state.products.products) {
      await this.$store.dispatch("getProductsAction");
    }

    this.setInitialOptions();
  },

  methods: {
    addOrderProduct() {
      this.$Progress.start();
      (this.form.order_id = this.$route.params.id),
        (this.form.product_id = this.selectedProduct.id);
      this.form
        .post("/api/order/details/add")
        .then((response) => {
          if (response.data.status) {
            this.$Progress.finish();
            this.hideModal();
            this.getOrderProducts();

            Toast.fire({
              icon: "success",
              text: "Product added Successfully !",
            });
          } else {
            this.$Progress.fail();
            this.$swal.fire({
              icon: "error",
              title: "Failed !!!",
              text: response.data.message,
            });
          }
        })
        .catch((error) => {
          this.$Progress.fail();
          this.$swal.fire({
            icon: "error",
            title: "Failed !!!",
            text: error.message,
          });
        });
    },
    searchProducts(search, loading) {
      if (search.length > 3) {
        this.$Progress.start();
        axios.post("/api/products/search", { search }).then((response) => {
          if (response.data.length > 0) {
            this.options = response.data;
          }
          this.$Progress.finish();
        });
      }
    },

    setInitialOptions() {
      this.options = this.$store.state.products.products.data;
    },

    getOrderProducts() {
      this.$Progress.start();
      const id = this.$route.params.id;
      axios.get(`/api/orders/order/${id}`).then((response) => {
        this.title = response.data.order_number;
        this.products = response.data.order_details;
        this.$Progress.finish();
      });
    },

    deleteOrderProduct(id) {
      this.$swal
        .fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
          if (result.isConfirmed) {
            this.deleteProduct(id);
          }
        });
    },

    deleteProduct(id) {
      this.$Progress.start();
      axios
        .delete(`/api/order/details/delete/${id}`)
        .then((response) => {
          if (response.data.status) {
            this.$Progress.finish();
            this.getOrderProducts();
            this.$swal.fire({
              icon: "success",
              title: "Sucess !",
              text: response.data.message,
            });
          } else {
            this.$Progress.fail();
            this.$swal.fire({
              icon: "error",
              title: "Failed !!!",
              text: response.data.message,
            });
          }
        })
        .catch((error) => {
          this.$Progress.fail();
          this.$swal.fire({
            icon: "error",
            title: "Failed !!!",
            text: error.message,
          });
        });
    },

    showModal() {
      $("#order-products-modal").modal({
        backdrop: "static",
        keyboard: false,
      });
      $("#order-products-modal").modal("show");
    },

    hideModal() {
      this.selectedProduct = "";
      this.form.clear();
      this.form.reset();
      $("#order-products-modal").modal("hide");
    },
  },
};
</script>
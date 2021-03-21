<template>
  <div class="container-fluid row justify-content-center">
    <div class="col-md-11">
      <div class="card mt-2">
        <div class="card-header card-header-content">
          <h3 class="green card-heading">
            <i class="fas fa-clipboard-list"></i> Current Orders
          </h3>
          <a href="" @click.prevent="showModal()" class="btn btn-sm btn-success"
            ><i class="fas fa-plus mr-2"></i>Add New Order</a
          >
        </div>
        <table class="table table-striped">
          <thead>
            <th>#</th>
            <th>Order Number</th>
            <th>Total Order Products</th>
            <th>Date</th>
            <th>Order Details</th>
            <th>Delete</th>
          </thead>
          <tbody v-if="$store.state.orders.orders">
            <tr
              v-for="(order, index) in $store.state.orders.orders.data"
              :key="index"
            >
              <td>{{ index + 1 }}</td>
              <td>{{ order.order_number }}</td>
              <td>
                {{ order.order_details.length }}
              </td>
              <td>
                {{ order.created_at | formatDate }}
              </td>
              <td>
                <router-link
                  :to="`/orders/${order.id}`"
                  class="btn btn-sm btn-primary"
                  ><i class="fas fa-eye"></i
                ></router-link>
              </td>
              <td>
                <a href="" @click.prevent="deleteOrder(order.id)" class="btn btn-sm btn-danger"
                  ><i class="fas fa-trash"></i
                ></a>
              </td>
            </tr>
          </tbody>
          <tfoot>
            <th>#</th>
            <th>Order Number</th>
            <th>Total Order Products</th>
            <th>Date</th>
            <th>Order Details</th>
            <th>Delete</th>
          </tfoot>
        </table>
        <div class="card-footer" v-if="$store.state.orders.orders">
          <pagination
            :data="$store.state.orders.orders"
            :limit="5"
            @pagination-change-page="getNextOrdersAction"
            align="right"
          ></pagination>
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
          <form @submit.prevent="addOrder()">
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
import { mapActions } from "vuex";
import { Form } from "vform";
import Toast from "../common/toast";

export default {
  data() {
    return {
      options: [],
      selectedProduct: {},
      form: new Form({
        order_id: null,
        product_id: null,
      }),
    };
  },

   async mounted() {
     this.getOrdersAction();

    if (!this.$store.state.products.products) {
      await this.$store.dispatch("getProductsAction");
    }

    this.setInitialOptions();
  },

  methods: {
    ...mapActions(["getOrdersAction", "getNextOrdersAction"]),

    addOrder() {
      this.$Progress.start();
      (this.form.order_id = this.$route.params.id),
        (this.form.product_id = this.selectedProduct.id);
      this.form
        .post("/api/orders/add")
        .then((response) => {
          if (response.data.status) {
            this.$Progress.finish();
            this.hideModal();
            console.log(response.data)
            this.$router.push({name: 'order-detail', params: {id: response.data.order.id}})

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

    deleteOrder(id) {

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
            this.delete(id);
          }

        });
    },

    delete(id) {
      this.$Progress.start();
      axios
        .delete(`/api/orders/delete/${id}`)
        .then((response) => {
          if (response.data.status) {
            this.$Progress.finish()
            this.getOrdersAction()
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
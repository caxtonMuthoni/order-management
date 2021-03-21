<template>
  <div class="container">
    <div class="row">
      <div class="col-md-11">
        <div class="card mt-3">
          <div class="card-header card-header-content">
            <h3 class="indigo card-heading">{{ title }} Supply Products</h3>
            <a
              href=""
              @click.prevent="showModal(false)"
              class="btn btn-sm btn-success"
              ><i class="fas fa-plus mr-2"></i>Add Supplier Product</a
            >
          </div>
          <div class="body">
            <table class="table table-striped">
              <thead>
                <th>#</th>
                <th>Product</th>
                <th>Product Description</th>
                <!-- <th>update</th> -->
                <th>Delete</th>
              </thead>
              <tbody v-if="products">
                <tr v-for="(product, index) in products" :key="index">
                  <td>{{ index + 1 }}</td>
                  <td>{{ product.product.name }}</td>
                  <td>{{ product.product.description }}</td>
                  <!-- <td>
                    <a
                      href=""
                      @click.prevent="showModal(true, product)"
                      class="btn btn-sm btn-primary"
                      ><i class="fas fa-edit"></i
                    ></a>
                  </td> -->
                  <td>
                    <a
                      href=""
                      @click.prevent="deleteSupplierProduct(product.id)"
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
                <!-- <th>update</th> -->
                <th>Delete</th>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="supplier-product-modal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modelTitleId"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg modal-dialog-centere" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-if="isUpdating" class="modal-title indigo">
              <i class="fas fa-plus mr-2"></i> Update Supplier product
            </h5>
            <h5 v-else class="modal-title indigo">
              <i class="fas fa-edit mr-2"></i> Create new Supplier product
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
          <form @submit.prevent="addSupplierProduct()">
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
              <button
                type="button"
                @click="hideModal"
                class="btn btn-danger"
                data-dismiss="modal"
              >
                Close
              </button>
              <button v-if="isUpdating" type="submit" class="btn btn-primary">
                Update Product
              </button>
              <button v-else type="submit" class="btn btn-primary">
                Save Product
              </button>
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
      products: [],
      title: "",
      isUpdating: false,
      item: "",
      options: [],
      selectedProduct: {},
      form: new Form({
        supplier_id: null,
        products_id: null,
      }),
    };
  },

  async mounted() {
    this.getSupplierProducts();
    if (!this.$store.state.products.products) {
      await this.$store.dispatch("getProductsAction");
    }
    this.setInitialOptions();
  },

  methods: {
    ...mapActions["getProductsAction"],

    addSupplierProduct() {
      this.$Progress.start();
      (this.form.supplier_id = this.$route.params.id),
        (this.form.products_id = this.selectedProduct.id);
      this.form
        .post("/api/supplier/products/add")
        .then((response) => {
          if (response.data.status) {
            this.$Progress.finish();
            this.hideModal();
            this.getSupplierProducts();

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

    getSupplierProducts() {
      this.$Progress.start();
      const id = this.$route.params.id;
      axios.get(`/api/suppliers/supplier/${id}`).then((response) => {
        this.title = response.data.name;
        this.products = response.data.supplier_products;
        this.$Progress.finish();
      });
    },

    setInitialOptions() {
      this.options = this.$store.state.products.products.data;
    },

    showModal(updating, product) {
      this.isUpdating = updating;
      if (updating) {
      }
      $("#supplier-product-modal").modal({
        backdrop: "static",
        keyboard: false,
      });
      $("#supplier-product-modal").modal("show");
    },

    deleteSupplierProduct(id) {
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
        .delete(`/api/supplier/products/delete/${id}`)
        .then((response) => {
          if (response.data.status) {
            this.$Progress.finish();
            this.getSupplierProducts();
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

    hideModal() {
      this.selectedProduct = "";
      this.form.clear();
      this.form.reset();
      $("#supplier-product-modal").modal("hide");
    },
  },
};
</script>
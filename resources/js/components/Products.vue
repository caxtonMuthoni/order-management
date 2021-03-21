<template>
  <div class="container-fluid">
    <div class="card mt-2">
      <div class="card-header card-header-content">
        <h3 class="blue card-heading">
          <i class="fab fa-product-hunt"></i> Available products
        </h3>
        <a
          href=""
          @click.prevent="showModal(false)"
          class="btn btn-sm btn-success"
          ><i class="fas fa-plus mr-2"></i>Add New Product</a
        >
      </div>
      <table class="table table-striped">
        <thead>
          <th>#</th>
          <th>Product</th>
          <th>Description</th>
          <th>Quantity</th>
          <th>Update</th>
          <th>Delete</th>
        </thead>
        <tbody v-if="$store.state.products.products">
          <tr
            v-for="(product, index) in $store.state.products.products.data"
            :key="index"
          >
            <td>{{ index + 1 }}</td>
            <td>{{ product.name }}</td>
            <td>{{ product.description }}</td>
            <td>{{ product.quantity }}</td>
            <td>
              <a
                href=""
                @click.prevent="showModal(true, product)"
                class="btn btn-sm btn-primary"
                ><i class="fas fa-edit"></i
              ></a>
            </td>
            <td>
              <a
                href=""
                @click.prevent="deleteProduct(product.id)"
                class="btn btn-sm btn-danger"
                ><i class="fas fa-trash"></i
              ></a>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <th>#</th>
          <th>Product</th>
          <th>Description</th>
          <th>Quantity</th>
          <th>Update</th>
          <th>Delete</th>
        </tfoot>
      </table>
      <div class="card-footer" v-if="$store.state.products.products">
        <pagination
          :data="$store.state.products.products"
          :limit="5"
          @pagination-change-page="getNextProductsAction"
          align="right"
        ></pagination>
      </div>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="product-modal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modelTitleId"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-if="isUpdating" class="modal-title blue">
              <i class="fas fa-edit mr-2"></i> Updating Product Details
            </h5>
            <h5 v-else class="modal-title blue">
              <i class="fas fa-plus mr-2"></i> Creating New Product
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
          <form
            @submit.prevent="
              isUpdating
                ? updateProductAction({ form, hideModal })
                : addProductAction({ form })
            "
          >
            <div class="modal-body">
              <div class="form-group row">
                <label
                  for="colFormLabelLg"
                  class="col-sm-2 col-form-label col-form-label-lg"
                  >Name</label
                >
                <div class="col-sm-10">
                  <input
                    v-model="form.name"
                    type="text"
                    required
                    class="form-control form-control-lg"
                    id="name"
                    placeholder="Product Name"
                    name="name"
                    :class="{ 'is-invalid': form.errors.has('name') }"
                  />
                  <has-error :form="form" field="name"></has-error>
                </div>
              </div>

              <div class="form-group row">
                <label
                  for="colFormLabelLg"
                  class="col-sm-2 col-form-label col-form-label-lg"
                  >Quantity</label
                >
                <div class="col-sm-10">
                  <input
                    v-model="form.quantity"
                    type="text"
                    required
                    class="form-control form-control-lg"
                    id="quantity"
                    placeholder="Product Quantity"
                    name="quantity"
                    :class="{ 'is-invalid': form.errors.has('quantity') }"
                  />
                  <has-error :form="form" field="quantity"></has-error>
                </div>
              </div>

              <div class="form-group row">
                <label
                  for="colFormLabelLg"
                  class="col-sm-2 col-form-label col-form-label-lg"
                  >Description</label
                >
                <div class="col-sm-10">
                  <input
                    v-model="form.description"
                    type="text"
                    required
                    class="form-control form-control-lg"
                    id="description"
                    placeholder="Product Description"
                    name="description"
                    :class="{ 'is-invalid': form.errors.has('description') }"
                  />
                  <has-error :form="form" field="description"></has-error>
                </div>
              </div>
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
              <button
                type="submit"
                :disabled="form.busy"
                class="btn btn-primary"
                v-if="isUpdating"
              >
                Update product
              </button>

              <button
                type="submit"
                :disabled="form.busy"
                class="btn btn-primary"
                v-else
              >
                Save product
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
export default {
  data() {
    return {
      isUpdating: false,
      form: new Form({
        id: "",
        name: "",
        description: "",
        quantity: "",
      }),
    };
  },

  mounted() {
    this.getProductsAction();
  },

  methods: {
    ...mapActions([
      "getProductsAction",
      "getNextProductsAction",
      "addProductAction",
      "updateProductAction",
      "deleteProductAction"
    ]),

    deleteProduct(id) {
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
            this.deleteProductAction(id)
          }
        });
    },

    showModal(updating, product) {
      if (updating) {
        this.isUpdating = updating;
        this.form.id = product.id;
        this.form.name = product.name;
        this.form.quantity = product.quantity;
        this.form.description = product.description;
      }
      $("#product-modal").modal({
        backdrop: "static",
        keyboard: false,
      });
      $("#product-modal").modal("show");
    },

    hideModal() {
      $("#product-modal").modal("hide");
    },
  },
};
</script>
<template>
  <div class="row">
    <div class="col-md-12">
      <el-tabs v-model="activeTab" 
               :stretch="true" 
               type="border-card"
               @tab-click="onTabChange">
        <el-tab-pane label="Appraisals" name="appraisals">
          <client-orders v-if="activeTab == 'appraisals'" />
        </el-tab-pane>
        <el-tab-pane v-if="isManager" 
                     label="Company Orders" 
                     name="company_orders">
          <company-orders v-if="activeTab == 'company_orders'" />
        </el-tab-pane>
        <el-tab-pane v-if="isDocuvaultEnabled" 
                     label="DocuVault" 
                     name="docuVault">
          <docu-vault v-if="activeTab == 'docuVault'" />
        </el-tab-pane>
        <el-tab-pane v-if="isAVMEnabled" 
                     label="AVM" 
                     name="avm">
          <avm v-if="activeTab == 'avm'" />
        </el-tab-pane>
        <el-tab-pane label="Support" name="support">
          <support v-if="activeTab == 'support'" />
        </el-tab-pane>
        <el-tab-pane label="Resources" name="resources">
          <resources />
        </el-tab-pane>
      </el-tabs>
    </div>
  </div>
</template>

<script>
const ClientOrders = () =>
  import(/* webpackChunkName: "appraisals-client-orders" */ "@/components/appraisals/orders/client");
const CompanyOrders = () =>
  import(/* webpackChunkName: "appraisals-company-orders" */ "@/components/appraisals/orders/company");
const Resources = () =>
  import(/* webpackChunkName: "dashboard-resources" */ "@/components/resources/guidelines");
const Support = () =>
  import(/* webpackChunkName: "dashboard-support" */ "@/components/support/index");
const DocuVault = () =>
  import(/* webpackChunkName: "docuvault-orders" */ "@/components/docuvault/orders/client");
const Avm = () =>
  import(/* webpackChunkName: "avm-orders" */ "@/components/avm/orders/client");
import { mapGetters } from "vuex";
export default {
  components: {
    ClientOrders,
    CompanyOrders,
    DocuVault,
    Resources,
    Support,
    Avm
  },
  data() {
    return {
      activeTab: "appraisals"
    };
  },
  computed: {
    ...mapGetters("auth", ["isManager", "isDocuvaultEnabled", "isAVMEnabled"])
  },
  methods: {
    onTabChange(tab) {
      this.activeTab = tab.name;
    }
  }
};
</script>

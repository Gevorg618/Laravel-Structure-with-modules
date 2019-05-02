<template>
  <div class="row">
    <div class="col-md-12">
      <b-notification :closable="false" 
                      type="is-info" 
                      has-icon>
        <p>
          Docuvault gives you the ability to manage completed orders and control when your borrower is notified to view and download their final appraisal and supplemental documents. 
        </p>
        <p>
          Below you will see a list of all appraisal orders completed through Landmark Network. You can easily send notifications to the borrower by clicking the "Notify" button next to each order. Once the borrower has accessed the DocuVault and upon download of the document, the indications will change to "Yes" under each respective column. Once the borrower has downloaded the documents, the "Notify" button on the right will change to a "Download" button, allowing you to download a PDF confirmation that the borrower has accessed DocuVault and provide you with a list of all the documents downloaded along with the date and time of download. 
        </p>
      </b-notification>
    </div>
  </div>
</template>

<script>
export default {};
</script>

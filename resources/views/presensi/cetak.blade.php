<style>
    body {
        background: rgb(204, 204, 204);
    }

    page {
        background: white;
        display: block;
        margin: 0 auto;
        margin-bottom: 0.5cm;
        box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
    }

    page[size="A4"] {
        width: 21cm;
        height: 29.7cm;
    }

    page[size="A4"][layout="landscape"] {
        width: 29.7cm;
        height: 21cm;
    }

    page[size="A3"] {
        width: 29.7cm;
        height: 42cm;
    }

    page[size="A3"][layout="landscape"] {
        width: 42cm;
        height: 29.7cm;
    }

    page[size="A5"] {
        width: 14.8cm;
        height: 21cm;
    }

    page[size="A5"][layout="landscape"] {
        width: 21cm;
        height: 14.8cm;
    }

    @media print {

        body,
        page {
            margin: 0;
            box-shadow: 0;
        }
    }
</style>
<page size="A4" layout="landscape"></page>

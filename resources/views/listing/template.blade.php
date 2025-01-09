<table
align="center"
cellpadding="0"
cellspacing="0"
class="tr-caption-container"
style="margin-left: auto; margin-right: auto"
>
<tbody>
@foreach ($data['processed_images'] as $image)
<tr>
<td style="text-align: center">
<a
href="{{ $image }}"
style="margin-left: auto; margin-right: auto"
>
<img
class="baseimg"
id="baseimg"
border="0"
data-original-height="555"
data-original-width="555"
height="320"
src="{{ $image }}"
width="320"
/>
</a>
</td>
</tr>
@endforeach
<tr>
<td class="tr-caption" style="text-align: center">
{{ $data["selling_price"] }}-{{ $data["mrp"] }}
</td>
</tr>
</tbody>
</table>

@foreach ($data['multiple_images'] as $image)
<div class="separator" style="clear: both; text-align: center">
    <a href="{{ $image }}" style="margin-left: 1em; margin-right: 1em">
        <img
            class="img"
            border="0"
            data-original-height="600"
            data-original-width="970"
            height="198"
            src="{{ $image }}"
            width="320"
        />
    </a>
</div>
@endforeach

<div class="separator" style="clear: both; text-align: left">
    <a
        href="{{ $data['url'] }}"
        id="url"
        style="text-align: justify"
        target="_blank"
    >
        BUY AT INSTAMOJO
    </a>
</div>

<div><span>[Shipping Cost = Standard Mode, Expedite Mode]</span></div>
<div>
    {!! $data['description'] !!}
    <span><!--more--></span><br />
    <!--This is default post template. You can edit and change it-->

    <div class="product_detail_content">
        <div class="dt_header">Product Details</div>
        <div class="productDetails dt_content">
            <table class="detail-ui-grid">
                <tbody>
                    <tr>
                        <td class="detailsku">SKU / BOOK Code:</td>
                        <td itemprop="sku">{{ $data["sku"] }}</td>
                    </tr>
                    <tr>
                        <td class="detailcolor">Publisher:</td>
                        <td itemprop="color">{{ $data["publication"] }}</td>
                    </tr>
                    <tr>
                        <td class="detailguide">Author:</td>
                        <td itemprop="author">{{ $data["author_name"] }}</td>
                    </tr>
                    <tr>
                        <td class="detailfrom">Binding Type:</td>
                        <td itemprop="binding">{{ $data["binding"] }}</td>
                    </tr>
                    <tr>
                        <td class="detailcoupon">No. of Pages:</td>
                        <td>{{ $data["pages"] }}</td>
                    </tr>
                    <tr>
                        <td class="detailisbn10">ISBN-10:</td>
                        <td itemprop="isbn10">{{ $data["isbn_10"] }}</td>
                    </tr>
                    <tr>
                        <td class="detailisbn13">ISBN-13:</td>
                        <td itemprop="isbn13">{{ $data["isbn_13"] }}</td>
                    </tr>
                    <tr>
                        <td class="detailedition">Edition:</td>
                        <td itemprop="edition">{{ $data["edition"] }}</td>
                    </tr>
                    <tr>
                        <td class="detaillanguage">Language:</td>
                        <td itemprop="language">{{ $data["language"] }}</td>
                    </tr>
                    <tr>
                        <td class="detailpublishyear">Publish Year:</td>
                        <td itemprop="publishyear">{{ $data["publish_year"] }}</td>
                    </tr>
                    <tr>
                        <td class="weight">Weight (g):</td>
                        <td itemprop="weight">{{ $data["weight"] }}</td>
                    </tr>
                    <tr>
                        <td class="detailcondition">Product Condition:</td>
                        <td itemprop="condition">{{ $data["condition"] }}</td>
                    </tr>
                    <tr>
                        <td class="readingage">Reading Age:</td>
                        <td itemprop="age">{{ $data["reading_age"] }}</td>
                    </tr>
                    <tr>
                        <td class="detailsku">Country of Origin:</td>
                        <td itemprop="origin">{{ $data["country_origin"] }}</td>
                    </tr>
                    <tr>
                        <td class="genre">Genre:</td>
                        <td itemprop="genre">{{ $data["genre"] }}</td>
                    </tr>
                    <tr>
                        <td class="manufacturer">Manufacturer:</td>
                        <td itemprop="manufacturer">{{ $data["manufacturer"] }}</td>
                    </tr>
                    <tr>
                        <td class="importer">Importer:</td>
                        <td itemprop="importer">{{ $data["importer"] }}</td>
                    </tr>
                    <tr>
                        <td class="packer">Packer:</td>
                        <td itemprop="packer">{{ $data["packer"] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="dt_header">Product Description</div>
        <div class="pbl box dtmoredetail dt_content">
            {!! $data['description'] !!}
        </div>
    </div>

    <div class="dt_header">Easy Return and Delivery Policy</div>
    <div class="box dtreturns dt_content">
        <div>
            Since EXAM360 SHOP (Open Store) is capable to deliver all Indian Pin-codes
            which covers 1.55 Lakhs Pin-codes throughout India. While adding any item to
            cart / purchasing anything through this website, users are requested to
            enter the valid address with Name, C/O, House Name/No., Area / Locality,
            City, District, Landmark, State, Pin-code, Mobile No, Email carefully. If
            any issues occurred due to invalid / incomplete address in such cases
            EXAM360 will not take any responsibility for the losses or damages. <br />
            <br />
            We practice Easy Return / Exchange / Refund policy for Buyer Protection, So
            if you experience any difficulties like (Wrong Item delivered) with any of
            the product received, you can raise request under Return / Exchange Policy
            through the EXAM360 Customer support Portal i.e. https://support.exam360.in/
            with valid details. Once our executive validate the case properly, we will
            take necessary steps as per the Policy Standard & we will be more than happy
            to help you to solve your issues ASAP.<br />
            <br />
            While receiving the item from any of our courier partner, users are
            requested to check the packaging item properly, If you feel the item is
            delivering by the logistic partners in tampering conditions, we request our
            buyers not to accept the product & instantly make a call to the below
            mentioned HELPLINE Numbers. And, If you purchased an item that was not
            satisfactory, in such cases we will issue Return / Refund as per the current
            policy guidelines.
            <br /><br />
            <strong>Selling Price & Shipping Fee:</strong> In Product details page we
            have clearly mentioned the Selling Price & Shipping Fees separately, So,
            when you click on "BUY NOW" You will be charges Selling Price + Shipping
            Fees. The Shipping fees may different for each products depends on the
            weight of the Product. The shipping cost includes the courier charges,
            packaging charges, transport charges, fuel charges and other charges.
            Sometimes the total payable amount may be different for Instamojo Payment
            Gateway. Buyers are requested to check before purchasing the products. After
            making the purchase we may not allow or consider users to modify.
            <br />
            - For more information about return policy
            <a
                href="https://publication.exam360.in/p/return-policy.html"
                target="_blank"
            >
                <strong>CLICK HERE</strong>
            </a>
            <br />
            - If you have questions about the product, please contact our dedicated
            Customer Support Team at
            <a href="https://support.exam360.in/">ECG Portal</a>.
        </div>
    </div>

    <div class="dt_header">Delivery Information</div>
    <div class="deliveryInfo box dt_content">
        <div></div>
        <div>
            EXAM360 SHOP is capable to deliver All India Pin-codes by the govt.
            recognised courier partners with full security. <br />
            Generally the courier partner support delivery during business hours (9:00 -
            20:00) Mon - Sat: Fedex, Trackon, Gati, Delhivery, Indian Post.<br />
            <br />
            <a href="https://support.exam360.in/" target="_blank"
            >The actual delivery time may differ from the given Estimated timeline.
            For more information on delivery time and shipping charges, please refer
            to our dedicated support team through <strong>ECG Portal</strong>.</a
            >
            <br />
            <br />
            <strong>Note:</strong> Expected Delivery time may differ from the Estimated 
            or Projected delivery time & it does not include holidays.
        </div>
    </div>
</div>

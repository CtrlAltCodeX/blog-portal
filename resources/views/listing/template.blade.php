<div style="text-align: justify;">
    <table align="center" cellpadding="0" cellspacing="0" class="tr-caption-container" style="margin-left: auto; margin-right: auto;">
        <tbody>
            @foreach ($data['processed_images'] as $image)
            <tr>
                <td style="text-align: center;">
                    <a href="{{ $image }}" style="margin-left: auto; margin-right: auto;">
                        <img class='baseimg' id='baseimg' border="0" data-original-height="555" data-original-width="555" height="320" src="{{ $image }}" width="320" />
                    </a>
                </td>
            </tr>
            @endforeach

            <tr>
                <td class="tr-caption" style="text-align: center;"><span id='selling'>{{ $data['selling_price'] }}</span>-<span id='mrp'>{{ $data['mrp'] }}</span></td>
            </tr>
        </tbody>
    </table>

    <br />

    @foreach ($data['multiple_images'] as $image)
    <div class="separator" style="clear: both; text-align: center;">
        <a href="{{ $image }}" style="margin-left: 1em; margin-right: 1em;">
            <img class='img' border="0" data-original-height="600" data-original-width="970" height="198" src="{{ $image }}" width="320" />
        </a>
    </div>
    @endforeach

    <br />

    <div class="separator" style="clear: both; text-align: left;">
        <a href="{{ $data['url'] }}" id='url' style="text-align: justify;" target="_blank">
            BUY AT INSTAMOJO
        </a>
    </div>

    <span>
        [Shipping Cost = Standard Mode, Expedite Mode]
    </span>
</div>

<div style="text-align: justify;">{!! $data['description'] !!}</div>

<div>
    <div style="text-align: justify;"><br /></div>
    <div style="text-align: justify;"><b>About the Author:</b></div>
    <div style="text-align: justify;" id='author'>{{ $data['about_author'] }}</div>
    <div style="text-align: justify;"><br /></div>
    <div style="text-align: justify;"><b>Search Key -&nbsp;</b>{{ $data['search_key'] }}</div>
    <div><span><!--more--></span><br />
        <!--This is default post template. You can edit and change it-->

        <div class="product_detail_content">
            <div class="dt_header">
                Product Details</div>
            <div class="productDetails dt_content">
                <table class="detail-ui-grid">
                    <tbody>
                        <tr>
                            <td class="detailsku">SKU, Publisher</td>
                            <td itemprop="sku" id='sku'>{{ $data['sku'] }}</td>
                        </tr>
                        <tr>
                            <td class="detailcolor">Publisher</td>
                            <td itemprop="color" id='publication'>{{ $data['publication'] }}</td>
                        </tr>
                        <tr>
                            <td class="detailguide">Author, Edition</td>
                            <td><span id='author_name'>{{ $data['author_name'] }}</span>, <span id='edition'>{{ $data['edition'] }}</span>, <span id='lang'>{{ $data['language'] }}</span></td>
                        </tr>
                        <tr>
                            <td class="detailguide">Binding, Type</td>
                            <td>{{$data['binding']}}</td>
                        </tr>

                        <tr>
                            <td class="detailcoupon">No. of Pages</td>
                            <td id='page_no'>{{ $data['pages'] }}</td>
                        </tr>

                        <tr>
                            <td class="detailcoupon">Weight</td>
                            <td id='weight'>{{ $data['weight'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="dt_header">
                Product Description</div>
            <div class="pbl box dtmoredetail dt_content"><span style="text-align: justify;" id="desc">{!! $data['description'] !!}</span>
                <div>
                    <div style="text-align: justify;"><br /></div>
                    <div style="text-align: justify;"><b>About the Author:</b></div>
                    <div style="text-align: justify;">{{ $data['about_author'] }}</div>
                    <div style="text-align: justify;"><br /></div>
                </div><b style="text-align: justify;">Search Key -&nbsp;</b><span style="text-align: justify;" id='search_key'>{{ $data['search_key'] }}</span>. <br />
            </div>
        </div>
        <div class="dt_header">
            Easy Return and Delivery Policy</div>
        <div class="box dtreturns dt_content">
            <div>
                Since EXAM360 SHOP (Open Store) is capable to deliver all Indian Pin-codes which covers 1.55 Lakhs
                Pin-codes throughout India. While Adding any item to cart / Purchasing anything through this website,
                users are requested to enter valid address with Name, C/O, House Name/No., Area / Locality, City,
                District, Landmark, State, Pincode, Mobile No, Email. If any issues occurred due to invalid / incomplete
                address in such cases Exam360 will not take any responsibility. <br />
                <br />
                We practice Easy Return/Exchange policy for Buyer Protection, So if you experience any difficulties like
                (Wrong Item delivered ) with any of the product received, you can raise request under Return / Exchange
                Policy through written E-mail at exam360.in@gmail.com with valid Footage. Once we validate the case in
                details we will initiate as per the Policy Standard &amp; we will be more than happy to help you to
                solve your issues ASAP.<br />
                <br />
                While receiving the item from any of our courier partner, users are requested to check the packaging
                item properly, If you feel any tampering we request not to accept the packet &amp; instantly make a call
                to the below mentioned HELPLINE with your Order Details. And, If you purchased an item that was not
                Satisfactory, in such cases we will issue Return/ Refund if case is genuine!
                <br /><br />
                <strong>Selling Price &amp; Shipping Fee:</strong> In Product details we have clearly mentioned the
                Selling Price &amp; Shipping fees seperately, So, when you "BUY NOW" You will be charges S.P+Shipping
                Fees. The Shipping fees may different for each products depends on the weight of the Product. Sometimes
                the Total Payable Amount may be diiferent from Instamojo also. Buyers are requested to check before
                purchasing the books. After purchasing we maynot allow users to modify.
                <br />
                - For more information about return policy <a href="https://publication.exam360.in/p/return-policy.html" target="_blank"> <strong>CLICK HERE</strong> </a>
                <br />
                - If you have questions about the product, please contact Customer Care at <a href="https://publication.exam360.in/p/contact.html">Exam360.in@gmail.com</a>.
            </div>
        </div>
        <div class="dt_header">
            Delivery Information</div>
        <div class="deliveryInfo box dt_content">
            <div>
            </div>
            <div>
                EXAM360 SHOP is capable to deliver ALL INDIAN PINCODES by the best courier partners with full security.
                <br />
                We only support delivery during business hours (9:00-20:00) Mon - Sat by renowned courier partners like:
                Fedex, Trackon, Gati, Delhivery, Indian Post.<br />
                <br />
                <a href="https://publication.exam360.in/p/delivery-policy.html" target="_blank">For more information on
                    delivery time and shipping charges, please refer to <strong>Click Here</strong>.</a>
                <br />
                <br />
                <strong>**Note: Expected Delivery time does not include Holidays.**</strong>
            </div>
        </div>
    </div>
</div>
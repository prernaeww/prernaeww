@include('template.header')
    <!-- hero-white-button -->
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs" align="center" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_bg-white o_px-md o_py-xl o_xs-py-md o_sans o_text-md o_text-light" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 19px;line-height: 28px;background-color: #ffffff;color: #82899a;padding-left: 24px;padding-right: 24px;padding-top: 64px;padding-bottom: 64px;">
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                      <tbody>
                        <tr>
                          <td class="o_bb-primary" height="40" width="32" style="border-bottom: 1px solid #126de5;">&nbsp; </td>
                          <td rowspan="2" class="o_sans o_text o_text-secondary o_px o_py" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;padding-left: 16px;padding-right: 16px;padding-top: 16px;padding-bottom: 16px;">
                            <img src="{{URL('images/shopping_cart-48-primary.png')}}" width="48" height="48" alt="" style="max-width: 48px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;">
                          </td>
                          <td class="o_bb-primary" height="40" width="32" style="border-bottom: 1px solid #126de5;">&nbsp; </td>
                        </tr>
                        <tr>
                          <td height="40">&nbsp; </td>
                          <td height="40">&nbsp; </td>
                        </tr>
                        <tr>
                          <td style="font-size: 8px; line-height: 8px; height: 8px;">&nbsp; </td>
                          <td style="font-size: 8px; line-height: 8px; height: 8px;">&nbsp; </td>
                        </tr>
                      </tbody>
                    </table>
                    <!-- <h2 class="o_heading o_text-dark o_mb-xxs" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 4px;color: #242b3d;font-size: 30px;line-height: 39px;">Thank you for your purchase!</h2> -->
                    <h2 class="o_heading o_text-dark o_mb-xxs" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 4px;color: #242b3d;font-size: 30px;line-height: 39px;text-align: center !important;">Order Completed</h2>
                    <p style="margin-top: 0px;margin-bottom: 0px;text-align: center !important;">{{$message}}</p>
                  </td>
                </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    <!-- invoice_header -->
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs" align="center" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_re o_bg-white o_px o_pt-xs o_hide-xs" align="center" style="font-size: 0;vertical-align: top;background-color: #ffffff;padding-left: 16px;padding-right: 16px;padding-top: 8px;">
                    <!--[if mso]><table cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td width="400" align="left" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-4" style="display: inline-block;vertical-align: top;width: 100%;max-width: 400px;">
                      <div class="o_px-xs o_sans o_text-xs o_left" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;text-align: left;padding-left: 8px;padding-right: 8px;">
                        <p class="o_text-light" style="color: #82899a;margin-top: 0px;margin-bottom: 0px;">Item</p>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="100" align="center" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-1" style="display: inline-block;vertical-align: top;width: 100%;max-width: 100px;">
                      <div class="o_px-xs o_sans o_text-xs o_center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;text-align: center;padding-left: 8px;padding-right: 8px;">
                        <p class="o_text-light" style="color: #82899a;margin-top: 0px;margin-bottom: 0px;">Qty</p>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="100" align="right" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-1" style="display: inline-block;vertical-align: top;width: 100%;max-width: 100px;">
                      <div class="o_px-xs o_sans o_text-xs o_right" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;text-align: right;padding-left: 8px;padding-right: 8px;">
                        <p class="o_text-light" style="color: #82899a;margin-top: 0px;margin-bottom: 0px;">Price</p>
                      </div>
                    </div>
                    <!--[if mso]></td></tr><tr><td colspan="3" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_px-xs" style="padding-left: 8px;padding-right: 8px;">
                      <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                        <tbody>
                          <tr>
                            <td class="o_re o_bb-light" style="font-size: 9px;line-height: 9px;height: 9px;vertical-align: top;border-bottom: 1px solid #d3dce0;">&nbsp; </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if mso]></td></tr></table><![endif]-->
                  </td>
                </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    <!-- product-lg -->    
    <?php foreach ($orderItem['order_products'] as $key => $row)
    {      
      $total_prodcut_price = $row['price'] * $row['qty']
      ?>
      <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
        <tbody>
          <tr>
            <td class="o_bg-light o_px-xs" align="center" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
              <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
                <tbody>
                  <tr>
                    <td class="o_re o_bg-white o_px o_pt" align="center" style="font-size: 0;vertical-align: top;background-color: #ffffff;padding-left: 16px;padding-right: 16px;padding-top: 16px;">
                      <div class="o_col o_col-1 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 100px;">
                        <div class="o_px-xs o_sans o_text o_center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;text-align: center;padding-left: 8px;padding-right: 8px;">
                            <p style="margin-top: 0px;margin-bottom: 0px;"><a class="o_text-primary" href="#" style="text-decoration: none;outline: none;color: #126de5;">
                            <img src={{$row['image'] }} width="84" height="84" alt="" style="max-width: 84px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;border-radius: 12%;"></a></p>
                        </div>
                      </div>
                      <div class="o_col o_col-3 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 300px;">
                        <div style="font-size: 16px; line-height: 16px; height: 16px;">&nbsp; </div>
                        <div class="o_px-xs o_sans o_text o_text-light o_left o_xs-center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #82899a;text-align: left;padding-left: 8px;padding-right: 8px;">
                            <h4 class="o_heading o_text-dark o_mb-xxs" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 4px;color: #242b3d;font-size: 18px;line-height: 23px;"><?php echo  $row['product_name']; ?></h4>
                            <p class="o_text-secondary o_mb-xs" style="color: #424651;margin-top: 0px;margin-bottom: 8px;">Size : <?php echo  $row['product']['quantity']; ?> <?php echo  $row['product']['measurement_name']; ?></p>
                        </div>
                      </div>
                      <div class="o_col o_col-1 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 100px;">
                        <div class="o_hide-xs" style="font-size: 16px; line-height: 16px; height: 16px;">&nbsp; </div>
                        <div class="o_px-xs o_sans o_text o_text-secondary o_center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;text-align: center;padding-left: 8px;padding-right: 8px;">
                            <p class="o_mb-xxs" style="margin-top: 0px;margin-bottom: 4px;"><span class="o_hide-lg" style="display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;visibility: hidden;">Quantity:&nbsp; </span><?php echo  $row['qty']; ?></p>
                        </div>
                      </div>
                      <div class="o_col o_col-1 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 100px;">
                        <div class="o_hide-xs" style="font-size: 16px; line-height: 16px; height: 16px;">&nbsp; </div>
                        <div class="o_px-xs o_sans o_text o_text-secondary o_right o_xs-center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;text-align: right;padding-left: 8px;padding-right: 8px;">
                          <p class="o_mb-xxs" style="margin-top: 0px;margin-bottom: 4px;">
                          <span class="o_hide-lg" style="display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;visibility: hidden;">Price:&nbsp; </span>
                          <?php echo  $currency; ?> <?php echo  $total_prodcut_price; ?>
                          </p>
                        </div>
                      </div>
                      <div class="o_px-xs" style="padding-left: 8px;padding-right: 8px;">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                            <tbody>
                            <tr>
                                <td class="o_re o_bb-light" style="font-size: 16px;line-height: 16px;height: 16px;vertical-align: top;border-bottom: 1px solid #d3dce0;">&nbsp; </td>
                            </tr>
                            </tbody>
                        </table>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>
    <?php } ?>
    <!-- invoice-total-light -->
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs" align="center" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_re o_bg-white o_px-md o_py" align="right" style="font-size: 0;vertical-align: top;background-color: #ffffff;padding-left: 24px;padding-right: 24px;padding-top: 16px;padding-bottom: 16px;">
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                      <tbody>
                        <tr>
                          <td width="252" class="o_px o_pb o_pt-xs o_bg-ultra_light o_br" align="left" style="background-color: #ebf5fa;border-radius: 4px;padding-left: 16px;padding-right: 16px;padding-top: 8px;padding-bottom: 16px;">
                            <table width="100%" role="presentation" cellspacing="0" cellpadding="0" border="0">
                              <tbody>
                                <tr>
                                  <td width="50%" class="o_pt-xs" align="left" style="padding-top: 8px;">
                                    <p class="o_sans o_text o_text-secondary" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;">Subtotal</p>
                                  </td>
                                  <td width="50%" class="o_pt-xs" align="right" style="padding-top: 8px;">
                                    <p class="o_sans o_text o_text-secondary" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;">{{$currency}} {{$sub_total}}</p>
                                  </td>
                                </tr>
                                <tr>
                                  <td width="50%" class="o_pt-xs" align="left" style="padding-top: 8px;">
                                    <p class="o_sans o_text o_text-secondary" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;">Tax</p>
                                  </td>
                                  <td width="50%" class="o_pt-xs" align="right" style="padding-top: 8px;">
                                    <p class="o_sans o_text o_text-secondary" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;">{{$currency}} {{$tax}}</p>
                                  </td>
                                </tr>
                                <tr>
                                  <td class="o_pt o_bb-light" style="border-bottom: 1px solid #d3dce0;padding-top: 16px;">&nbsp; </td>
                                  <td class="o_pt o_bb-light" style="border-bottom: 1px solid #d3dce0;padding-top: 16px;">&nbsp; </td>
                                </tr>
                                <tr>
                                  <td width="50%" class="o_pt" align="left" style="padding-top: 16px;">
                                    <p class="o_sans o_text o_text-secondary" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;"><strong>Total </strong></p>
                                  </td>
                                  <td width="50%" class="o_pt" align="right" style="padding-top: 16px;">
                                    <p class="o_sans o_text o_text-secondary" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;"><strong>{{$currency}} {{$total}}</strong></p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    <!-- customer-details-plain -->
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs" align="center" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_re o_bg-white o_px o_pb-md" align="center" style="font-size: 0;vertical-align: top;background-color: #ffffff;padding-left: 16px;padding-right: 16px;padding-bottom: 24px;">
                    <!--[if mso]><table cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td width="300" align="center" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-3 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 300px;">
                      <div style="font-size: 24px; line-height: 24px; height: 24px;">&nbsp; </div>
                      <div class="o_px-xs o_sans o_text-xs o_text-secondary o_left o_xs-center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;color: #424651;text-align: left;padding-left: 8px;padding-right: 8px;">
                        <p class="o_mb-xs" style="margin-top: 0px;margin-bottom: 8px;"><strong>Store Information</strong></p>
                        <p class="o_mb-md" style="margin-top: 0px;margin-bottom: 24px;">{{$store_name}}<br>
                        {{$address}}<br>                          
                        <p class="o_mb-xs" style="margin-top: 0px;margin-bottom: 8px;"><strong>Pickup Method</strong></p>
                        <p style="margin-top: 0px;margin-bottom: 0px;">{{$pickup_method}}</p>
                      </div>
                    </div>
                    <!--[if mso]></td><td width="300" align="left" valign="top" style="padding: 0px 8px;"><![endif]-->
                    <div class="o_col o_col-3 o_col-full" style="display: inline-block;vertical-align: top;width: 100%;max-width: 300px;">
                      <div style="font-size: 24px; line-height: 24px; height: 24px;">&nbsp; </div>
                      <div class="o_px-xs o_sans o_text-xs o_text-secondary o_left o_xs-center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;color: #424651;text-align: left;padding-left: 8px;padding-right: 8px;">
                        <p class="o_mb-xs" style="margin-top: 0px;margin-bottom: 8px;"><strong>Pickup Details</strong></p>
                        <p class="o_mb-md" style="margin-top: 0px;margin-bottom: 24px;">{{$pickup_notes}}<br>
                        {{$vehicle_description}}<br>
                        <p class="o_mb-xs" style="margin-top: 0px;margin-bottom: 8px;"><strong>Reached Time</strong></p>
                        <p style="margin-top: 0px;margin-bottom: 0px;">{{$reached}}</p>
                      </div>
                    </div>
                    <!--[if mso]></td></tr></table><![endif]-->
                  </td>
                </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    <!-- spacer -->
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
      <tbody>
        <tr>
          <td class="o_bg-light o_px-xs" align="center" style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
            <!--[if mso]><table width="632" cellspacing="0" cellpadding="0" border="0" role="presentation"><tbody><tr><td><![endif]-->
            <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
              <tbody>
                <tr>
                  <td class="o_bg-white" style="font-size: 24px;line-height: 24px;height: 24px;background-color: #ffffff;">&nbsp; </td>
                </tr>
              </tbody>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    <!-- footer-light-2cols -->
@include('template.footer')

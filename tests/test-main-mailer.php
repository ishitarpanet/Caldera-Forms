<?php

/**
 * Coverage for main mailer
 *
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 */
class Test_Main_Mailer extends Caldera_Forms_Mailer_Test_Case{



    /**
     * Test that the contact form import utility works
     *
     * @since 1.5.9
     *
     * @group form
     * @group email
     *
     * @covers Caldera_Forms_Forms::import_form()
     * @covers Caldera_Forms_Test_Case::import_contact_form()
     * @covers Caldera_Forms_Test_Case::recursive_cast_array()
     */
    public function test_contact_form_import(){
        $form_id = $this->import_contact_form();
        $form = Caldera_Forms_Forms::get_form($form_id);
        $this->assertSame($form_id, $form['ID']);

        $this->assertArrayHasKey('fields', $form);
        $this->assertArrayHasKey('layout_grid', $form);
        $this->assertArrayHasKey('pinned', $form);
        $this->assertArrayHasKey('fields', $form);
        $this->assertArrayHasKey('fld_29462', $form['fields']);
        $this->assertArrayHasKey('slug', $form['fields']['fld_29462']);
        $this->assertEquals('header', $form['fields']['fld_29462']['slug']);
        $this->assertArrayHasKey('fld_8768091', $form['fields']);
        $this->assertArrayHasKey('config', $form['fields']['fld_29462']);
        $this->assertTrue(is_array($form['fields']['fld_29462']['config']));
    }

    /**
     * Test that the to setting of main email is set properly
     *
     * @since 1.5.9
     *
     * @group email
     *
     * @covers Test_Main_Mailer:submit_contact_form()
     * @covers Test_Main_Mailer:mailer_callback()
     */
    public function test_capture_entry_id(){
        $this->submit_contact_form();
        $this->assertTrue(is_numeric($this->entry_id));
    }

    /**
     * Test that the to setting of main email is set properly
     *
     * @since 1.5.9
     *
     * @group email
     *
     * @covers Caldera_Forms_Save_Final::do_mailer()
     */
    public function test_to(){
        $this->submit_contact_form();
        $mailer = tests_retrieve_phpmailer_instance();
        $this->assertEquals('to@example.com', $mailer->get_recipient('to')->address);
        $expected = 'To: to@example.com, roy@roysivan.com';
        $this->assertTrue(strpos($mailer->get_sent()->header, $expected) > 0);

    }

    /**
     * Test that the FROM setting of main email is set properly
     *
     * @since 1.5.9
     *
     * @group email
     *
     * @covers Caldera_Forms_Save_Final::do_mailer()
     */
    public function test_from(){
        $this->submit_contact_form();
        $mailer = tests_retrieve_phpmailer_instance();
        $expected = 'From: Caldera Forms Notification <from@from.com>';
        $this->assertTrue(strpos($mailer->get_sent()->header, $expected) > 0);
    }

    /**
     * Test that the REPLYTO setting of main email is set properly
     *
     * @since 1.5.9
     *
     * @group email
     *
     * @covers Caldera_Forms_Save_Final::do_mailer()
     */
    public function test_replyto(){
        $this->submit_contact_form();
        $mailer = tests_retrieve_phpmailer_instance();
        $expected = 'Reply-To: roy@roysivan.com';
        $this->assertTrue(strpos($mailer->get_sent()->header, $expected) > 0);
    }

    /**
     * Test that the BCC setting of main email is set properly
     *
     * @since 1.5.9
     *
     * @group email
     *
     * @covers Caldera_Forms_Save_Final::do_mailer()
     */
    public function test_bcc(){
        $this->submit_contact_form();
        $mailer = tests_retrieve_phpmailer_instance();
        $expected = 'bcc1@example.com, bcc2@example2.com';
        $this->assertTrue(strpos($mailer->get_sent()->header, $expected) > 0);

    }

    /**
     * Test that the content of the email is correct
     *
     * @since 1.5.9
     *
     * @group email
     *
     * @covers Caldera_Forms_Save_Final::do_mailer()
     */
    public function test_content(){
        $this->submit_contact_form();
        $mailer = tests_retrieve_phpmailer_instance();
        $this->assertEquals('97cae2f8e53fc89902562dac3714475b', md5($mailer->get_sent()->body));
    }



}

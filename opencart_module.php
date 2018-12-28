<?php
/**
* Opencart 3.x module generator.
*
*
* License: GPL v.2.0
* Author: Dmitry Lazarev <http://dmitry-lazarev.ru>
* Version: 0.9
*
*/
$module_name=askModuleName();
define('UPLOAD_DIR', 'upload/');

define('ADMIN_DIR', UPLOAD_DIR.'admin/');
define('CATALOG_DIR', UPLOAD_DIR.'catalog/');

define('ADMIN_CONTROLLER_DIR', ADMIN_DIR.'controller/extension/module/');
define('ADMIN_LANGUAGE_DIR', ADMIN_DIR.'language/en-gb/extension/module/');
define('ADMIN_MODEL_DIR', ADMIN_DIR.'model/extension/module/');
define('ADMIN_VIEW_DIR', ADMIN_DIR.'view/template/extension/module/');

define('CATALOG_CONTROLLER_DIR', CATALOG_DIR.'controller/extension/module/');
define('CATALOG_LANGUAGE_DIR', CATALOG_DIR.'language/en-gb/extension/module/');
define('CATALOG_MODEL_DIR', CATALOG_DIR.'model/extension/module/');
define('CATALOG_VIEW_DIR', CATALOG_DIR.'view/theme/default/template/extension/module/');
$end_catalogs=array
(
    ADMIN_CONTROLLER_DIR,
    ADMIN_LANGUAGE_DIR,
    ADMIN_MODEL_DIR,
	  ADMIN_VIEW_DIR,

    CATALOG_CONTROLLER_DIR,
    CATALOG_LANGUAGE_DIR,
    CATALOG_MODEL_DIR,
    CATALOG_VIEW_DIR
);
global $end_catalogs;

if (isWritable()
) {
    makeDirs($end_catalogs);
    makeFiles($module_name, $end_catalogs);
    echo "Done. \n";
} else {
    echo "Unable to create module directories here: ".__DIR__."\n";
}
/**
 * Function askModuleName
 *
 * @return string
 */
function askModuleName()
{

    $fileName=getopt("f:");
    return $fileName['f'];
}
/**
 * Function isWritable
 *
 * @return boolean
 */
function isWritable()
{
    return is_writable('.');
}
/**
 * Function makeDirs
 *
 * $catalogs this is a catalogs
 *
 * @return null
 */
function makeDirs($catalogs)
{
    foreach ($catalogs as $catalog
    ) {
        file_exists($catalog) || mkdir($catalog, 0777, true);
    }
}
/**
 * $module_name test
 *
 * $php_catalogs tst
 *
 * @return null
 */
function makeFiles($module_name,$php_catalogs)
{
    $output='';
    foreach ($php_catalogs as $catalog) {
        if ($catalog == ADMIN_VIEW_DIR || $catalog == CATALOG_VIEW_DIR){
            $f=fopen($catalog.$module_name.'.twig', 'at');
        } else {
            $f=fopen($catalog.$module_name.'.php', 'at');
        }

        switch ($catalog)
        {
          case ADMIN_LANGUAGE_DIR:
      		case CATALOG_LANGUAGE_DIR: $output=getLanguageFile($module_name); break;
      		case ADMIN_CONTROLLER_DIR: $output=getAdminControllerFile($module_name); break;
      		case ADMIN_MODEL_DIR: $output=getAdminModelFile($module_name); break;
      		case ADMIN_VIEW_DIR: $output=getAdminViewFile(); break;
      		case CATALOG_CONTROLLER_DIR: $output=getCatalogControllerFile($module_name); break;
      		case CATALOG_MODEL_DIR: $output=getCatalogModelFile($module_name); break;
      		case CATALOG_VIEW_DIR: $output=getCatalogViewFile(); break;
      		default: $output="";
        }
        fwrite($f, $output);
        fclose($f);
    }
}
/** */
function getCatalogViewFile()
{
    $template=<<<TEMPLATE
<?php
TEMPLATE;
    return $template;
}
/** */
function getCatalogModelFile($name)
{
	$temp=ucfirst(implode('', explode('-', $name)));
    $template=<<<TEMPLATE
<?php
class ModelExtensionModule{$temp} extends Model
{

}
TEMPLATE;
    return $template;
}
/** */
function getCatalogControllerFile($name)
{
	$temp=ucfirst(implode('', explode('-', $name)));
    $template=<<<TEMPLATE
<?php
class ControllerExtensionModule{$temp} extends Controller
{
	public function index()
	{
		\$this->load->language('extension/module/{$name}');

		\$this->load->model('catalog/model/extension/module/{$name}');

		return \$this->load->view('extension/module/{$name}', \$data);
	}
}
TEMPLATE;
    return $template;
}
/** */
function getAdminViewFile()
{
    $template=<<<TEMPLATE
{{ header }}{{ column_left }}
<div id="content">
	  <div class="page-header">
		<div class="container-fluid">
		  <div class="pull-right">
			<button type="submit" form="form-module" data-toggle="tooltip" title="{{ button_save }}" class="btn btn-primary"><i class="fa fa-save"></i></button>
			<a href="{{ cancel }}" data-toggle="tooltip" title="{{ button_cancel }}" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
		  <h1>{{ heading_title }}</h1>
		  <ul class="breadcrumb">
			{% for breadcrumb in breadcrumbs %}
			<li><a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a></li>
			{% endfor %}
		  </ul>
		</div>
	  </div>
	  <div class="container-fluid">
		{% if error_warning %}
		<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> {{ error_warning }}
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		{% endif %}
		<div class="panel panel-default">
		  <div class="panel-heading">
			<h3 class="panel-title"><i class="fa fa-pencil"></i> {{ text_edit }}</h3>
		  </div>
		  <div class="panel-body">
			<form action="{{ action }}" method="post" enctype="multipart/form-data" id="form-module" class="form-horizontal">
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-name">{{ entry_name }}</label>
				<div class="col-sm-10">
				  <input type="text" name="name" value="{{ name }}" placeholder="{{ entry_name }}" id="input-name" class="form-control" />
				  {% if error_name %}
				  <div class="text-danger">{{ error_name }}</div>
				  {% endif %}
				</div>
			  </div>
			  <!-- Control elements will be here. -->
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-status">{{ entry_status }}</label>
				<div class="col-sm-10">
				  <select name="status" id="input-status" class="form-control">
					{% if status %}
					<option value="1" selected="selected">{{ text_enabled }}</option>
					<option value="0">{{ text_disabled }}</option>
					{% else %}
					<option value="1">{{ text_enabled }}</option>
					<option value="0" selected="selected">{{ text_disabled }}</option>
					{% endif %}
				  </select>
				</div>
			  </div>
			</form>
		  </div>
		</div>
	  </div>
</div>
{{ footer }}
TEMPLATE;
    return $template;
}
/** */
function getAdminModelFile($name)
{
	$temp=ucfirst(implode('', explode('-', $name)));
    $template=<<<TEMPLATE
<?php
class ModelExtensionModule{$temp} extends Model
{

}
TEMPLATE;
    return $template;
}
/** */
function getAdminControllerFile($name)
{
	$temp=ucfirst(implode('', explode('-', $name)));
	$ltemp=implode('', explode('-', $name));
    $template=<<<TEMPLATE
<?php
class ControllerExtensionModule{$temp} extends Controller
{
		private \$error = array();

		public function index() {
			\$this->load->language('extension/module/{$ltemp}');

			\$this->document->setTitle(\$this->language->get('heading_title'));

			\$this->load->model('setting/module');

			if ((\$this->request->server['REQUEST_METHOD'] == 'POST') && \$this->validate()) {
				if (!isset(\$this->request->get['module_id'])) {
					\$this->model_setting_module->addModule('{$ltemp}', \$this->request->post);
				} else {
					\$this->model_setting_module->editModule(\$this->request->get['module_id'], \$this->request->post);
				}

				\$this->session->data['success'] = \$this->language->get('text_success');

				\$this->response->redirect(\$this->url->link('marketplace/extension', 'user_token=' . \$this->session->data['user_token'] . '&type=module', true));
			}

			if (isset(\$this->error['warning'])) {
				\$data['error_warning'] = \$this->error['warning'];
			} else {
				\$data['error_warning'] = '';
			}

			if (isset(\$this->error['name'])) {
				\$data['error_name'] = \$this->error['name'];
			} else {
				\$data['error_name'] = '';
			}

			\$data['breadcrumbs'] = array();

			\$data['breadcrumbs'][] = array(
				'text' => \$this->language->get('text_home'),
				'href' => \$this->url->link('common/dashboard', 'user_token=' . \$this->session->data['user_token'], true)
			);

			\$data['breadcrumbs'][] = array(
				'text' => \$this->language->get('text_extension'),
				'href' => \$this->url->link('marketplace/extension', 'user_token=' . \$this->session->data['user_token'] . '&type=module', true)
			);

			if (!isset(\$this->request->get['module_id'])) {
				\$data['breadcrumbs'][] = array(
					'text' => \$this->language->get('heading_title'),
					'href' => \$this->url->link('extension/module/{$ltemp}', 'user_token=' . \$this->session->data['user_token'], true)
				);
			} else {
				\$data['breadcrumbs'][] = array(
					'text' => \$this->language->get('heading_title'),
					'href' => \$this->url->link('extension/module/{$ltemp}', 'user_token=' . \$this->session->data['user_token'] . '&module_id=' . \$this->request->get['module_id'], true)
				);
			}

			if (!isset(\$this->request->get['module_id'])) {
				\$data['action'] = \$this->url->link('extension/module/{$ltemp}', 'user_token=' . \$this->session->data['user_token'], true);
			} else {
				\$data['action'] = \$this->url->link('extension/module/{$ltemp}', 'user_token=' . \$this->session->data['user_token'] . '&module_id=' . \$this->request->get['module_id'], true);
			}

			\$data['cancel'] = \$this->url->link('marketplace/extension', 'user_token=' . \$this->session->data['user_token'] . '&type=module', true);

			if (isset(\$this->request->get['module_id']) && (\$this->request->server['REQUEST_METHOD'] != 'POST')) {
				\$module_info = \$this->model_setting_module->getModule(\$this->request->get['module_id']);
			}

			if (isset(\$this->request->post['name'])) {
				\$data['name'] = \$this->request->post['name'];
			} elseif (!empty(\$module_info)) {
				\$data['name'] = \$module_info['name'];
			} else {
				\$data['name'] = '';
			}

			if (isset(\$this->request->post['status'])) {
				\$data['status'] = \$this->request->post['status'];
			} elseif (!empty(\$module_info)) {
				\$data['status'] = \$module_info['status'];
			} else {
				\$data['status'] = '';
			}

			\$data['header'] = \$this->load->controller('common/header');
			\$data['column_left'] = \$this->load->controller('common/column_left');
			\$data['footer'] = \$this->load->controller('common/footer');

			\$this->response->setOutput(\$this->load->view('extension/module/{$ltemp}', \$data));
		}

		protected function validate() {
			if (!\$this->user->hasPermission('modify', 'extension/module/{$ltemp}')) {
				\$this->error['warning'] = \$this->language->get('error_permission');
			}

			if ((utf8_strlen(\$this->request->post['name']) < 3) || (utf8_strlen(\$this->request->post['name']) > 64)) {
				\$this->error['name'] = \$this->language->get('error_name');
			}

			return !\$this->error;
		}
}
TEMPLATE;
    return $template;
}
/**
 * Function get_language_file
 *
 * @return string
 */
function getLanguageFile($name)
{
	$temp=ucfirst($name);
    $template=<<<TEMPLATE
<?php
\$_['heading_title'] = '{$temp}';
\$_[''] = '';
\$_[''] = '';
\$_[''] = '';
\$_[''] = '';
TEMPLATE;
    return $template;
}

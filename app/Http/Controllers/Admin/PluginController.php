<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Acelle\Model\Plugin;

class PluginController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->admin->can('read', new \Acelle\Model\Plugin())) {
            return $this->notAuthorized();
        }

        // If admin can view all sending domains
        if (!$request->user()->admin->can("readAll", new \Acelle\Model\Plugin())) {
            $request->merge(array("admin_id" => $request->user()->admin->id));
        }

        // exlude customer seding plugins
        $request->merge(array("no_customer" => true));

        $plugins = \Acelle\Model\Plugin::search($request);

        return view('admin.plugins.index', [
            'plugins' => $plugins,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        if (!$request->user()->admin->can('read', new \Acelle\Model\Plugin())) {
            return $this->notAuthorized();
        }

        // If admin can view all sending domains
        if (!$request->user()->admin->can("readAll", new \Acelle\Model\Plugin())) {
            $request->merge(array("admin_id" => $request->user()->admin->id));
        }

        // exlude customer seding plugins
        $request->merge(array("no_customer" => true));

        $plugins = \Acelle\Model\Plugin::search($request)->paginate($request->per_page);

        return view('admin.plugins._list', [
            'plugins' => $plugins,
        ]);
    }

    /**
     * Install/Upgrage plugins.
     *
     * @return \Illuminate\Http\Response
     */
    public function install(Request $request)
    {
        $plugin = new \Acelle\Model\Plugin();
        $plugin->status = \Acelle\Model\Plugin::STATUS_ACTIVE;

        // authorize
        if (!$request->user()->admin->can('install', $plugin)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            sleep(2);
            $plugin = Plugin::uploadAndSave($request);
            
            $request->session()->flash('alert-success', trans('messages.plugin.installed'));

            // success
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.plugin.installed'),
                'return' => action('Admin\PluginController@index'),
            ], 201);
        }

        return view('admin.plugins.install', [
            'server' => $plugin,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $items = \Acelle\Model\Plugin::whereIn('uid', explode(',', $request->uids));

        foreach ($items->get() as $item) {
            // authorize
            if ($request->user()->admin->can('delete', $item)) {
                $item->delete();
            }
        }

        // Redirect to my lists page
        echo trans('messages.plugins.deleted');
    }

    /**
     * Disable sending server.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request)
    {
        $items = \Acelle\Model\Plugin::whereIn('uid', explode(',', $request->uids));

        foreach ($items->get() as $item) {
            // authorize
            if ($request->user()->admin->can('disable', $item)) {
                $item->disable();
            }
        }

        // Redirect to my lists page
        echo trans('messages.plugins.disabled');
    }

    /**
     * Disable sending server.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request)
    {
        $items = \Acelle\Model\Plugin::whereIn('uid', explode(',', $request->uids));

        foreach ($items->get() as $item) {
            // authorize
            if ($request->user()->admin->can('enable', $item)) {
                $item->enable();
            }
        }

        // Redirect to my lists page
        echo trans('messages.plugins.enabled');
    }

    /**
     * Email verification server display options form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function options(Request $request, $uid=null)
    {
        if ($uid) {
            $plugin = \Acelle\Model\Plugin::findByUid($uid);
        } else {
            $plugin = new \Acelle\Model\Plugin($request->all());
            $options = $plugin->getOptions();
        }

        return view('admin.plugins._options', [
            'server' => $plugin,
            'options' => $options,
        ]);
    }
}

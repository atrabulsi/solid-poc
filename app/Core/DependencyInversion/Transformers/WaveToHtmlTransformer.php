<?php

namespace App\Core\DependencyInversion\Transformers;


use App\Core\DependencyInversion\Entities\Wave;
use App\Core\DependencyInversion\Interfaces\WaveTransformerInterface;

class WaveToHtmlTransformer implements WaveTransformerInterface
{
    private string $data = '';

    public function write(Wave $wave)
    {
        $this->data = "
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{$wave->getId()}</td>
                        <td>{$wave->getTitle()}</td>
                    </tr>
                </tbody>
            </table>
        ";
            [
            'id' => $wave->getId(),
            'title' => $wave->getTitle(),
        ];
    }

    public function read(): string
    {
        return $this->data;
    }
}
